<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <title>MaNGOS Questfeedback Collector</title>
</head>

<body style="padding: 20px;">
    <div class="row">
        <div class="col-12" style="padding: 20px;">
            <?= $this->include('server_state') ?>
            <br>
            <div id="quest_table">
                <?= $this->include('quest_table') ?>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            getPage(1);
        });

        function searchForQuest(e)
        {
            if(e.keyCode == 13)
            {
                $.ajax({
                    type: "GET",
                    url: "home/getPage",
                    data: {
                        'name': $("#searchText").val()
                    },
                    success: function(data) {
                        $("#quest_table").html(data);
                    }
                });
            }
        }

        function getPage(page)
        {                
            $.ajax({
                type: "GET",
                url: "home/getPage",
                data: {
                    page,
                    'name': $("#searchText").val()
                },
                success: function(data) {
                    $("#quest_table").html(data);
                    
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }); 
        }

        function feedbackOk(idQuest) {
            console.log(idQuest);
            $.ajax({
                type: "POST",
                url: "home/feedbackOk",
                data: {
                    'id': idQuest
                },
                success: function(data) {
                    reloadQuestTable();
                }
            });
        }

        function feedbackKo(idQuest) {
            console.log(idQuest);
            $.ajax({
                type: "POST",
                url: "home/feedbackKo",
                data: {
                    'id': idQuest
                },
                success: function(data) {
                    reloadQuestTable();
                }
            });
        }
    </script>
</body>
<footer>
</footer>

</html>