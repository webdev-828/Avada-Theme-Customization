var shell = require('shelljs');

module.exports = function(grunt) {
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		po2mo: {
			options: {
			},
			files: {
				src: 'languages/*.po',
				expand: true,
			},
		},
		// Generate .pot translation file
		makepot: {
			target: {
				options: {
					type: 'wp-plugin',
					domainPath: 'languages',
				}
			}
		},
	});

	grunt.loadNpmTasks('grunt-po2mo');
	grunt.loadNpmTasks('grunt-wp-i18n');

	grunt.registerTask('default', ['makepot', 'po2mo']);
};
