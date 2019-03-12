;(function ( $, window, document, undefined ) {

	"use strict";

	var $plugin_name = "fusion_maps",
		$defaults 	 = {
			addresses: 	{},
			address_pin: true,
			animations: true,
			delay: 10, // delay between each address if over_query_limit is reached
			infobox_background_color: false,
			infobox_styling: 'default',
			infobox_text_color: false,
			map_style: 'default',
			map_type: 'roadmap',
			marker_icon: false,
			overlay_color: false,
			overlay_color_hsl: {}, // hue, saturation, lightness object
			pan_control: true,
			show_address: true,
			scale_control: true,
			scrollwheel: true,
			zoom: 9,
			zoom_control: true
		};

	// Plugin Constructor
	function Plugin( $element, $options ) {
		this.element 	= $element;
		this.settings 	= $.extend( {}, $defaults, $options );
		this._defaults 	= $defaults;
		this._name 		= $plugin_name;

		this.geocoder = new google.maps.Geocoder();
		this.next_address = 0;
		this.infowindow = new google.maps.InfoWindow();
		this.markers = [];
		this.query_sent = false;
		this.last_cache_index = 'none';
		this.bounds = new google.maps.LatLngBounds();

		this.init();
	}

	// Avoid Plugin.prototype conflicts
	$.extend(Plugin.prototype, {
		init: function() {
			var $map_options = {
					zoom: this.settings.zoom,
					mapTypeId: this.settings.map_type,
					scrollwheel: this.settings.scrollwheel,
					scaleControl: this.settings.scale_control,
					panControl: this.settings.pan_control,
					zoomControl: this.settings.zoom_control
				},
				$latlng, $styles,
				$isDraggable = $(document).width() > 640 ? true : false,
				$plugin_object = this;


			if( ! this.settings.scrollwheel ) {
				$map_options.draggable = $isDraggable;
			}

			if( ! this.settings.address_pin ) {
				this.settings.addresses = [ this.settings.addresses[0] ];
			}

			jQuery.each( this.settings.addresses, function( $index ) {
				if( this.cache == false ) {
					$plugin_object.last_cache_index = $index;
				}
			});

			if( this.settings.addresses[0].coordinates ) {
				$latlng = new google.maps.LatLng( this.settings.addresses[0].latitude, this.settings.addresses[0].longitude );
				$map_options.center = $latlng;
			}

			this.map = new google.maps.Map( this.element, $map_options );

			if( this.settings.overlay_color && this.settings.map_style == 'custom' ) {
				$styles = [
					{
						stylers: [
							{ hue: this.settings.overlay_color },
							{ lightness: this.settings.overlay_color_hsl.lum * 2 - 100 },
							{ saturation: this.settings.overlay_color_hsl.sat * 2 - 100 }
						]
					},
					{
						featureType: 'road',
						elementType: 'geometry',
						stylers: [
							{ visibility: 'simplified' }
						]
					},
					{
						featureType: 'road',
						elementType: 'labels'
					}
				];

				this.map.setOptions({
					styles: $styles
				});
			}

			// Reset zoom level after adding markers
			var bounds_changed = google.maps.event.addListener( this.map, 'bounds_changed', function() {
				var $latlng = new google.maps.LatLng( $plugin_object.settings.addresses[0].latitude, $plugin_object.settings.addresses[0].longitude );

				$plugin_object.map.setZoom( $plugin_object.settings.zoom );
				$plugin_object.map.setCenter( $latlng );

				google.maps.event.removeListener( bounds_changed );
			});

			this.next_geocode_request();
		},
		/**
		 * Geocoding Addresses
		 * @param  object $search object with address
		 * @return void
		 */
		geocode_address: function( $search, $index ) {
			var $plugin_object = this,
				$lat_lng_object,
				$address_object,
				$latitude,
				$longitude,
				$location,
				$cache = true,
				$query_sent;

			if( typeof( $search ) == 'object' && $search.cache == false ) {
				$cache = false;

				if( $search.coordinates === true ) {
					$lat_lng_object = new google.maps.LatLng( $search.latitude, $search.longitude );
					$address_object = { latLng: $lat_lng_object };
				} else {
					$address_object = { address: $search.address };
				}

				this.geocoder.geocode($address_object, function( $results, $status ) {
					var $latitude, $longitude, $location;

					if( $status == google.maps.GeocoderStatus.OK ) {
						// When coordiantes have been entered, bypass the geocoder results and use specified coordinates
						if ( $search.coordinates === true ) {
							$location = $lat_lng_object; // first location
							$latitude = jQuery.trim( $search.latitude );
							$longitude = jQuery.trim( $search.longitude );
						} else {
							$location = $results[0].geometry.location; // first location
							$latitude = $location.lat();
							$longitude = $location.lng();
						}

						$plugin_object.settings.addresses[ $index ]['latitude'] = $latitude;
						$plugin_object.settings.addresses[ $index ]['longitude'] = $longitude;

						if( $search.coordinates === true && $search.infobox_content === '' ) {
							$search.geocoded_address = $results[0].formatted_address;
						}

						// If first address is not a coordinate, set a center through address
						if( $plugin_object.next_address == 1 && ! $search.coordinates ) {
							$plugin_object.map.setCenter( $location );
						}

						if( $plugin_object.settings.address_pin ) {
							$plugin_object.create_marker( $search, $latitude, $longitude );
						}

						if ( $plugin_object.next_address == 0 ) {
							$plugin_object.map.setCenter( $location );
						}
					} else {
						// if over query limit, go back and try again with a delayed call
						if( $status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT ) {
							$plugin_object.next_address--;
							$plugin_object.settings.delay++;
						}
					}

					if ( $cache == false && $plugin_object.query_sent == false && $plugin_object.last_cache_index == $index ) {
						var $data = {
							action: 'fusion_cache_map',
							addresses: $plugin_object.settings.addresses,
							security: js_local_vars.admin_ajax_nonce
						};

						jQuery.post( js_local_vars.admin_ajax, $data );

						$plugin_object.query_sent = true;
					}

					$plugin_object.next_geocode_request();
				});
			} else if( typeof( $search ) == 'object' && $search.cache == true ) {
				$latitude = jQuery.trim( $search.latitude );
				$longitude = jQuery.trim( $search.longitude );
				$location = new google.maps.LatLng( $latitude, $longitude );

				if( $search.coordinates === true && $search.infobox_content === '' ) {
					$search.geocoded_address = $search.geocoded_address;
				}

				if( $plugin_object.settings.address_pin ) {
					$plugin_object.create_marker( $search, $latitude, $longitude );
				}

				if( $plugin_object.next_address == 0 ) {
					$plugin_object.map.setCenter( $location );
				}

				$plugin_object.next_geocode_request();
			}
		},
		create_marker: function( $address, $latitude, $longitude, $location ) {
			var $content_string,
				$marker_settings = {
					position: new google.maps.LatLng( $latitude, $longitude ),
					map: this.map
				},
				$marker;

			this.bounds.extend( $marker_settings.position );

			if( $address.infobox_content ) {
				$content_string = $address.infobox_content;
			} else {
				$content_string = $address.address;

				// Use google maps suggested address if coordinates were used
				if( $address.coordinates === true && $address.geocoded_address ) {
					$content_string = $address.geocoded_address;
				}
			}

			if( this.settings.animations ) {
				$marker_settings.animation = google.maps.Animation.DROP;
			}

			if( this.settings.map_style == 'custom' && this.settings.marker_icon == 'theme' ) {
				$marker_settings.icon = new google.maps.MarkerImage( $address.marker, null, null, null, new google.maps.Size( 37, 55 ) );
			} else if( this.settings.map_style == 'custom' && $address.marker ) {
				$marker_settings.icon = $address.marker;
			}

			$marker = new google.maps.Marker( $marker_settings );
			this.markers.push( $marker );

			this.create_infowindow( $content_string, $marker );

			if( this.next_address >= this.settings.addresses.length ) {
				this.map.fitBounds( this.bounds );
			}
		},
		create_infowindow: function( $content_string, $marker ) {
			var $info_window, $info_box_div, $info_box_options,
				$plugin_object = this;

			if( this.settings.infobox_styling == 'custom' && this.settings.map_style == 'custom' ) {
				$info_box_div = document.createElement('div');

				$info_box_options = {
					content: $info_box_div,
					disableAutoPan: true,
					maxWidth: 150,
					pixelOffset: new google.maps.Size( -125, 10 ),
					zIndex: null,
					boxStyle: {
						background: 'none',
						opacity: 1,
						width: '250px'
					},
					closeBoxMargin: '2px 2px 2px 2px',
					closeBoxURL: '//www.google.com/intl/en_us/mapfiles/close.gif',
					infoBoxClearance: new google.maps.Size( 1, 1 )
				};

				$info_box_div.className = 'fusion-info-box';
				$info_box_div.style.cssText = 'background-color:' + this.settings.infobox_background_color + ';color:' + this.settings.infobox_text_color  + ';';

				$info_box_div.innerHTML = $content_string;

				$info_window = new InfoBox( $info_box_options );
				$info_window.open( this.map, $marker );

				if( ! this.settings.show_address ) {
					$info_window.close( this.map, $marker );
				}

				google.maps.event.addListener( $marker, 'click', function() {
					var $map = $info_window.getMap();

					if( $map === null || typeof $map === 'undefined' ) {
						$info_window.open( $plugin_object.map, this );
					} else {
						$info_window.close( $plugin_object.map, this );
					}
				});
			} else {
				$info_window = new google.maps.InfoWindow({
					disableAutoPan: true,
					content: $content_string
				});

				if( this.settings.show_address ) {
					$info_window.show = true;
					$info_window.open( this.map, $marker );
				}

				google.maps.event.addListener( $marker, 'click', function() {
					var $map = $info_window.getMap();

					if( $map === null || typeof $map === 'undefined' ) {
						$info_window.open( $plugin_object.map, this );
					} else {
						$info_window.close( $plugin_object.map, this );
					}
				});
			}
		},
		/**
		 * Helps with avoiding OVER_QUERY_LIMIT google maps limit
		 * @return void
		 */
		next_geocode_request: function() {
			var $plugin_object = this;

			if ( $plugin_object.next_address < $plugin_object.settings.addresses.length ) {
				setTimeout( function() {
					$plugin_object.geocode_address( $plugin_object.settings.addresses[$plugin_object.next_address], $plugin_object.next_address );
					$plugin_object.next_address++;
				}, $plugin_object.settings.delay );
			}
		}
	});

	$.fn[ $plugin_name ] = function ( $options ) {
		this.each(function() {
			if ( ! $.data( this, 'plugin_' + $plugin_name ) ) {
				$.data( this, 'plugin_' + $plugin_name, new Plugin( this, $options ) );
			}
		});

		return this;
	};

})( jQuery, window, document );