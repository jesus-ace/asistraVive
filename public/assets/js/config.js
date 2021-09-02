var app = angular.module('Asistencias',['ngSanitize']).config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

    app.config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.xsrfCookieName = 'csrftoken';
        $httpProvider.defaults.xsrfHeaderName = 'X-CSRFToken';
    }]);
