<?php require_once __SITE_PATH . '/view/_header.php'; ?>

    <?php if( isset($a) ){ ?>
        <div class="content ">
            <h3> A </h3>
            <h5> #comments :  #movies </h5>
            <?php
            foreach ($a as $num_of_comments ){
                echo $num_of_comments['_id'] . ': &nbsp &nbsp ' . $num_of_comments['value']['count'] . '<br>';
            }
            ?>
        </div>
        <br><br>
    <?php
    }
    ?>
    <?php if( isset($b) ){ ?>
        <div class="content ">
            <h3> B </h3>
            <h5> precentage of uncommented movies </h5>
            <?php
            foreach ($b as $oneb ){
                $rez = number_format((float)$oneb['value']['avg'], 2, '.', '');
                echo $rez . "%";
            }

            ?>
        </div>
        <br><br>
        <?php
    }
    ?>
    <?php if( isset($c) ){ ?>
        <div class="content ">
            <h3> C </h3>
            <h5> authors and their most popular words </h5>
            <?php
            foreach ($c as $onec ){
                echo '<p class="bold">' . $onec['_id'] . '</p><br>';
                $words = $onec['value']['words'];
                $counts = $onec['value']['counts'];

                foreach ($words as $word){
                    echo $word . '<br>';
                }
                echo '<br><br>';
            }

            ?>
        </div>
        <br><br>
        <?php
    }
    ?>

    <br>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>


