<?php 
    /*
        File created by Ricardo Alonso
        e-mail: ricar.alonso@gmail.com
        Tel.: 651 17 71 69
        Linkedin: https://www.linkedin.com/in/ricardoalonsopeinado/
    */
?>

<?php

    class Vending
    {
        const Water = 0.65;
        const Juice = 1.00;
        const Soda = 1.50;

        private $Euros = 0;
        private static $returnMoney = 0;
        private static $result = array();
        private static $returnCoins = false;

        // Private functions
        private function getIngredient($ingredient) 
        {
            if ($this->$Euros >= $ingredient)
            {
                $this->$Euros -= $ingredient;

                array_push(self::$result, "Your product is here.");
                array_push(self::$result, "Your change:");
    
                $this->getChange();

                $this->$Euros = 0;
            }
            else
            {
                array_push(self::$result, "Sorry, but you haven't entered enough coins");
            }
        }

        private function getChange()
        {
            $resultCoins = strval($this->$Euros / 1);
            if (intval($resultCoins) > 0)
            {
                array_push(self::$result, intval($resultCoins) . ' X ' .number_format(1, 2, ".", ","));
                $this->$Euros -= (intval($resultCoins) * 1);
            }
            $resultCoins = strval($this->$Euros / 0.25);
            if (intval($resultCoins) > 0)
            {
                array_push(self::$result, intval($resultCoins) . ' X ' .number_format(0.25, 2, ".", ","));
                $this->$Euros -= (intval($resultCoins) * 0.25);
            }
            $resultCoins = strval($this->$Euros / 0.1);
            if (intval($resultCoins) > 0)
            {
                array_push(self::$result, intval($resultCoins) . ' X ' .number_format(0.10, 2, ".", ","));
                $this->$Euros -= (intval($resultCoins) * 0.1);
            }
            $resultCoins = strval($this->$Euros / 0.05);
            if (intval($resultCoins) > 0)
            {
                array_push(self::$result, intval($resultCoins) . ' X ' .number_format(0.05, 2, ".", ","));
                $this->$Euros -= (intval($resultCoins) * 0.05);
            }
        }

        // Public functions
        public function getCoins($euros)
        {
            $this->$Euros += $euros;
        }

        public function setCoins()
        {
            return $this->$Euros;
        }

        public function getReturnCoins()
        {
            self::$returnCoins = true;
            self::$returnMoney = $this->$Euros;
            $this->$Euros = 0;
        }

        public function returnCoins()
        {
            if (self::$returnCoins)
            {
                if (self::$returnMoney != 0)
                {
                    $this->$Euros = self::$returnMoney;

                    array_push(self::$result, "Your change:");
                    
                    $this->getChange();

                    $this->$Euros = 0;
                }
                else
                {
                    array_push(self::$result, "Sorry, but you haven't entered enough coins");
                }

                self::$returnCoins = false;
            }

            if (!empty(self::$result))
            {
                return self::$result;
            }

            return null;
        }

        public function getWater()
        {
            $this->getIngredient(self::Water);
        }

        public function getJuice()
        {
            $this->getIngredient(self::Juice);
        }

        public function getSoda()
        {
            $this->getIngredient(self::Soda);
        }
    }

?>

<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $objVending = null;

    if (!isset($_SESSION['objVending']))
    {
        $objVending = new Vending();
    }
    else
    {
        $objVending = $_SESSION['objVending'];
    }
    
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['euros'])){
            $objVending->getCoins($_POST['euros']);

            $_POST['euros'] = null;
            $_SERVER["REQUEST_METHOD"] = "";
        }

        if (isset($_POST['Water']))
        {
            $objVending->getWater();
        }

        if (isset($_POST['Juice']))
        {
            $objVending->getJuice();
        }

        if (isset($_POST['Soda']))
        {
            $objVending->getSoda();
        }

        if (isset($_POST['ReturnCoins']))
        {
            $objVending->getReturnCoins();
        }
    }

    $_SESSION['objVending'] = $objVending;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Vending Machine</title>

    <script>
        $( document ).ready(function() {
            $('#euros').val(0);
            $('#numEuros').val(0);
        });
    </script>
</head>

<body>
    <main role="main">
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-3">Vending Machine</h1>
                <p>This is a challenge for a simple Vending Machine.</p>
            </div>
        </div>
        <div class="container">
            <!-- Example row of columns -->
            <div class="row">
                <div class="col-md-12">
                    <h3 class="display-5">Insert Coin</h3>
                </div>
                <div class="col-md-12">
                    <a class="btn btn-primary btn-lg" href="javascript:get_euros(0.05)" role="button">0.05</a>
                    <a class="btn btn-primary btn-lg" href="javascript:get_euros(0.10)" role="button">0.10</a>
                    <a class="btn btn-primary btn-lg" href="javascript:get_euros(0.25)" role="button">0.25</a>
                    <a class="btn btn-primary btn-lg" href="javascript:get_euros(1.00)" role="button">1.00</a>
                </div>
            </div>
            <p></p>
            <div class="row">
                <div class="col-md-6 col-xs-0">
                </div>
                <div class="col-md-2 col-xs-6">
                    <h3 class="display-5">Total</h3>
                </div>
                <div class="col-md-4 col-xs-6">
                    <input class="form-control" type="number" id="numEuros" value="<?php echo number_format($objVending->setCoins(), 2, '.', ','); ?>" disabled>
                    <form action="" method="post" id="check-in">
                        <input type="hidden" id="euros" name="euros">
                    </form>
                </div>
            </div>
            <hr>
            <p></p>
            <div class="row">
                <div class="col-md-3" align="center">
                    <h2>Water</h2>
                    <svg width="10em" height="10em" viewBox="0 0 16 16" class="bi bi-droplet-half" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.21.8C7.69.295 8 0 8 0c.109.363.234.708.371 1.038.812 1.946 2.073 3.35 3.197 4.6C12.878 7.096 14 8.345 14 10a6 6 0 0 1-12 0C2 6.668 5.58 2.517 7.21.8zm.413 1.021A31.25 31.25 0 0 0 5.794 3.99c-.726.95-1.436 2.008-1.96 3.07C3.304 8.133 3 9.138 3 10c0 0 2.5 1.5 5 .5s5-.5 5-.5c0-1.201-.796-2.157-2.181-3.7l-.03-.032C9.75 5.11 8.5 3.72 7.623 1.82z"/>
                        <path fill-rule="evenodd" d="M4.553 7.776c.82-1.641 1.717-2.753 2.093-3.13l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448z"/>
                    </svg>
                    <p></p>
                    <h3>0.65</h3>
                    <p></p>
                    <p>
                        <form action="" method="post">
                            <button type="submit" class="btn btn-outline-primary" name="Water">
                                Buy 
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                                </svg>
                            </button>   
                        </form>
                    </p>
                </div>
                <div class="col-md-3" align="center">
                    <h2>Juice</h2>
                    <svg width="10em" height="10.0625em" viewBox="0 0 16 17" class="bi bi-cup-straw" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M13.964 1.18a.5.5 0 0 1-.278.65l-2.255.902-.462 2.08c.375.096.714.216.971.368.228.135.56.396.56.82 0 .046-.004.09-.011.132l-.955 9.068a1.28 1.28 0 0 1-.524.93c-.488.34-1.494.87-3.01.87-1.516 0-2.522-.53-3.01-.87a1.28 1.28 0 0 1-.524-.93L3.51 6.132A.78.78 0 0 1 3.5 6c0-.424.332-.685.56-.82.262-.154.607-.276.99-.372C5.824 4.614 6.867 4.5 8 4.5c.712 0 1.389.045 1.985.127l.527-2.37a.5.5 0 0 1 .302-.355l2.5-1a.5.5 0 0 1 .65.279zM9.768 5.608A13.991 13.991 0 0 0 8 5.5c-1.076 0-2.033.11-2.707.278A3.284 3.284 0 0 0 4.645 6c.146.073.362.15.648.222C5.967 6.39 6.924 6.5 8 6.5c.571 0 1.109-.03 1.588-.085l.18-.808zm.292 1.756a5.513 5.513 0 0 0 1.325-.297l-.845 8.03c-.013.12-.06.185-.102.214-.357.249-1.167.69-2.438.69-1.27 0-2.08-.441-2.438-.69-.041-.029-.09-.094-.102-.214l-.845-8.03c.137.046.283.088.435.126.774.194 1.817.308 2.95.308.742 0 1.445-.049 2.06-.137zm-5.593-1.48s.003.002.005.006l-.005-.006zm7.066 0l-.005.006a.026.026 0 0 1 .005-.006zM11.354 6a3.282 3.282 0 0 1-.703.235l.1-.446c.264.069.464.142.603.211z"/>
                    </svg>
                    <p></p>
                    <h3>1.00</h3>
                    <p></p>
                    <p>
                        <form action="" method="post">
                            <button type="submit" class="btn btn-outline-primary" name="Juice"> 
                                Buy
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                                </svg>
                            </button>   
                        </form>
                    </p>
                </div>
                <div class="col-md-3" align="center">
                    <h2>Soda</h2>
                    <svg width="10em" height="10em" viewBox="0 0 16 16" class="bi bi-lamp-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3z"/>
                        <path fill-rule="evenodd" d="M7.5 1l.276-.553a.25.25 0 0 1 .448 0L8.5 1h-1zm-.615 8h2.23C9.968 10.595 11 12.69 11 13.5c0 1.38-1.343 2.5-3 2.5s-3-1.12-3-2.5c0-.81 1.032-2.905 1.885-4.5z"/>
                    </svg>
                    <p></p>
                    <h3>1.50</h3>
                    <p></p>
                    <p>
                        <form action="" method="post">
                            <button type="submit" class="btn btn-outline-primary" name="Soda">
                                Buy
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                                </svg>
                            </button>   
                        </form>
                    </p>
                </div>
                <div class="col-md-3" align="center">
                    <form action="" method="post">
                        <button type="submit" class="btn btn-outline-secondary" name="ReturnCoins">Return Coin</button>
                    </form>
                    <p></p>
                    <ul class="list-group">
                        <?php
                            if ($objVending->returnCoins() != null)
                            {
                                $arrayResult = $objVending->returnCoins();
                                for($i=0;$i<count($arrayResult);$i++)
                                {
                                    ?>
                                        <li class="list-group-item"><?php echo $arrayResult[$i]; ?></li>
                                    <?php
                                }
                                ?>
                                    <script>
                                        $('#numEuros').val(0);
                                    </script>
                                <?php
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <hr>
        </div>
    </main>

    <footer class="container">
        <p>© ralonsop 2020</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script>
        function get_euros(euro)
        {
            $('#euros').val(euro);
            $('#check-in').submit();
        }
    </script>
</body>