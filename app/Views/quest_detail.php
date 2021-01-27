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
    <script>
        const whTooltips = {
            colorLinks: true,
            iconizeLinks: true,
            renameLinks: true
        };
    </script>
    <script src="https://wow.zamimg.com/widgets/power.js"></script>
    <title>MaNGOS Questfeedback Collector</title>
</head>

<body style="padding: 20px;">

    <?php if (isset($quest)) : ?>

        <div class="col-sm-12">
            <a href="/">
                <button type="button" class="btn btn-outline-dark">Back</button>
            </a>

            <div class="page-header">
                <h2><b><?= $quest['Title'] ?></b></h2>
            </div>
            <div class="well">
                Quest completed <b><?= $timesDone ?></b> times
                <br> <b>Min Level</b>: <?= $quest['MinLevel'] ?>
                <br> <b>Quest Level</b>: <?= $quest['QuestLevel'] ?>
                <br>
                <?php if ($quest['PrevQuestId'] != 0) : ?>
                    <b><a href="quest?id=<?= $quest['PrevQuestId'] ?>">Previous Quest</a></b>
                <?php endif; ?>
                <?php if ($quest['NextQuestId'] != 0) : ?>
                    | <b><a href="quest?id=<?= $quest['NextQuestId'] ?>">Next Quest</b></a>
                <?php endif; ?>
                <?php if ($quest['NextQuestInChain'] != 0) : ?>
                    | <b><a href="quest?id=<?= $quest['NextQuestInChain'] ?>">Next Quest</b></a>
                <?php endif; ?>
                <br>

                <?php if ($quest['SuggestedPlayers'] > 0) : ?>
                    <br> This quest is a Group Quest with at least <?= $quest['SuggestedPlayers'] ?> players <br>
                <?php endif; ?>
                <b>Rewards</b>:
                <div style="padding:20px; background-color: #c7c7c7!important;border-radius: 5px;">

                    <?php if ($RewardExperience > 0) : ?>
                        <b> You will Gain </b> <?= $RewardExperience ?> xp <br>
                    <?php endif; ?>

                    <?php if (isset($RewardMoney)) : ?>
                        <b> You will Earn </b>
                        <?php if ($RewardMoneyGold > 0) : ?>
                            <?= $RewardMoneyGold ?> <i class="fas fa-circle" style="color:goldenrod;"></i>
                        <?php endif; ?>
                        <?php if ($RewardMoneySilver > 0) : ?>
                            <?= $RewardMoneySilver ?> <i class="fas fa-circle" style="color:darkgray;"></i>
                        <?php endif; ?>
                        <?php if ($RewardMoneyCopper > 0) : ?>
                            <?= $RewardMoneyCopper ?> <i class="fas fa-circle" style="color:#b87333;"></i>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (isset($rewards) && (count($rewards) > 0)) : ?>
                        <b>Items: </b>
                        <?php foreach ($rewards as $reward) : ?>
                            <br><a href="#" data-wowhead="item=<?= $reward['entry'] ?>"><?= $reward['name'] ?></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if ($quest['RewardAtMaxLevel'] > 0) : ?>
                    <b> Reward at MAX Level</b>: <?= $quest['RewardAtMaxLevel'] ?>
                <?php endif; ?>
            </div>
            <div class="well">
                <b>Details</b>: <br><em> <?= $quest['Details'] ?> </em><br><br>
                <b>Objectives</b>: <br> <?= $quest['Objectives'] ?> <br><br>
            </div>
        </div>
    <?php endif; ?>

</body>
<footer>
</footer>

</html>