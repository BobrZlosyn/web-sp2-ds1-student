angular.module('ds1', [])
    .controller("adminScheduler", function ($scope, $window, $log, $http) {
        $scope.buttonClicked = function () {

            alert("ahoj");
            // info o aktualnim objektu
            console.log("kliknuto ");
            $scope.callSchedulerPost("test", 5, 100000);
        };

        /** metoda pro volani autocomplete - prihazuju si tam nazev fieldu, pro ktery to delam
         * vstupuje jako parametr do direktivy angucomplete-alt */
        $scope.callSchedulerPost = function (select, userInputString, timeoutPromise) {
            var request_data = {
                "select": select,
                "id": userInputString,
                "timeout": timeoutPromise,
                "base_url": $window.base_url
            };


            // vratit pro objekt angucomplete-alt
            var post_data = $http.post($scope.rest_config.scheduler_url, request_data);
            console.log($scope.rest_config.scheduler_url);
            post_data.then(function (response) {
                var request = response.data;
                if (request.msg === "ok") {

                    return (request.services_results);
                }
            });
        }
    });

/// tohle byla jen zkouska ale moc prostupny to neni (problem v pristupu ke Calendar a znovu vytvoreni)