module.exports = function(grunt) {
    grunt.initConfig({
        bump: {
            options: {
                files: ['package.json'],
                updateConfigs: [],
                commit: true,
                commitMessage: 'Release v%VERSION%',
                commitFiles: ['package.json'],
                createTag: true,
                tagName: '%VERSION%',
                tagMessage: 'Version bump %VERSION%',
                push: true,
                pushTo: 'origin'
            }
        }
    });

    grunt.loadNpmTasks('grunt-bump');

};