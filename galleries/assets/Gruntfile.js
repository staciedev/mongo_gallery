module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),    
    concat: {
      options: {        
        separator: ';'
      },
      dist1: {        
        src: ['js/admin/**/*.js'],        
        dest: 'js/admin.js'
      },
      dist2: {        
        src: ['js/public/**/*.js'],        
        dest: 'js/public.js'
      }
    },
    uglify: {
      options: {        
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
      },
      dist: {
        files: {
          'js/admin.min.js': 'js/admin.js',
          'js/public.min.js': 'js/public.js'
        }
      }
    },
    jshint: {      
      files: ['Gruntfile.js', 'js/public/**/*.js', 'js/admin/**/*.js'],      
      options: {        
        globals: {
          jQuery: true,
          console: true,
          module: true
        }
      }
    },
    watch: {
      files: ['<%= jshint.files %>'],
      tasks: ['jshint', 'dist', 'uglify']
    }
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');  
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');

  grunt.registerTask('dist', ['concat:dist1', 'concat:dist2']);
  grunt.registerTask('default', ['jshint']);
  grunt.registerTask('default', ['jshint', 'concat', 'uglify']);

};
