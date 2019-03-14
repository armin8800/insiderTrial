<!DOCTYPE html>
<html lang="en">
<head>
    <title>Insider Trial Day</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/app.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<img src="/img/loading.gif" id="loading" style="position: absolute;left: 50%;top:30%;">
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-5">
            <h2>League Table</h2>
            <table class="table">
                <thead>
                <tr>
                    <th>Team</th>
                    <th>PTS</th>
                    <th>P</th>
                    <th>W</th>
                    <th>D</th>
                    <th>L</th>
                    <th>GD</th>
                </tr>
                </thead>
                <tbody id="league-table">
                </tbody>
            </table>
        </div>
        <div class="col-lg-3 offset-lg-1">
            <div>
                <h2>Match Results</h2>
            </div>
            <div>
                <span>Week Number : </span><span id="week-number"></span>
            </div>
            <div id="result-week" class="col-lg-12">

            </div>
        </div>
        <div class="col-lg-3">
            <h4>Prediction of the Championship</h4>
            <div id="prediction-res" class="col-lg-12">

            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-5">
            <div class="row">
                <div class="col-2">
                    <button class="btn btn-primary">Play All</button>
                </div>
                <div class="col-2 offset-6">
                    <button class="btn btn-success" id="play" onclick="play()">Next Week</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-5">
            <div class="row">
                <div class="col-3">
                    <button class="btn btn-primary" id="resetBtn" onclick="resetAll()">Reset All Results</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ajaxSend(function(){
        $('#loading').fadeIn(50);
    });
    $(document).ajaxComplete(function(){
        $('#loading').fadeOut(50);
    });
    $(document).ready(function () {
        getWeek();
        getTable();
        getPrediction();
    });
    function getTable() {
        $.ajax({
            url: "/table",
            success: function (result) {
                var resultRow;
                $.each(result, function (key,team) {
                    resultRow +='<tr>' +
                        '<td>' + team["team_name"] + '</td>' +
                        '<td>' + team["points"] + '</td>' +
                        '<td>' + team["games"] + '</td>' +
                        '<td>' + team["win"] + '</td>' +
                        '<td>' + team["draw"] + '</td>' +
                        '<td>' + team["lose"] + '</td>' +
                        '<td>' + team["goals"] + '</td>' +
                        '</tr>';
                });
                $('#league-table').html(resultRow);

            }
        });
    }

    function getWeek() {
        $.ajax({
            url: "/currentWeek",
            success: function (result) {
                $('#week-number').html(result);
            }
        });
    }

    function CurrentWeekResult() {
        $.ajax({
            url: "/currentWeek",
            success: function (result) {
                $('#week-number').html(result);
            }
        });
    }

    function play() {
        var resultHtml = '';
        $.ajax({
            url: "/play",
            success: function (result) {
                if(result['success'] === 0) {
                    alert('All Matches played Already');
                }else {
                    $('#result-week').html('');
                    result = result["result"];
                    $.each(result, function (key,res) {
                        resultHtml = '';
                        resultHtml+='<div>' + res['home_team'] + ' ' + res['home_result'] + ' : ' +
                            res['guest_result'] + ' ' + res['guest_team'] + '</div>';
                        $('#result-week').append(resultHtml);
                    });
                    getWeek();
                    getTable();
                    getPrediction();
                }
            }
        });
    }

    function resetAll() {
        $.ajax({
            url: "/reset",
            success: function (result) {
                alert('All Results Reset!');
                getWeek();
                getTable();
                getPrediction();

            }
        });
    }

    function getPrediction() {
        $.ajax({
            url: "/prediction",
            success: function (result) {
                console.log(result);
                $('#prediction-res').html('');
                $.each(result,function (key,value) {
                    var eachrow = '';
                    eachrow +='<div>' + value["name"] + ' ' + value["value"] + '%' + '</div>';
                    $('#prediction-res').append(eachrow);
                });
            }
        });
    }
</script>
</body>
</html>
