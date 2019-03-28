<html>
    <head>
        <title>
            anu
        </title>
        <style>
            body {
                font-size: 12px;
                font-family: georgia;
            }
            header {
                text-align: center;
                border-bottom: 5px solid black;
            }
            .instansi {
                font-size: 18px;
            }
            .instansi.instansi--region {
                font-size: 15px;
                font-weight: bold;
            }
            .instansi.instansi--name {
                font-weight: bold;
            }
            .instansi.instansi--address {
                font-size: 12px;
            }
            
            .content {
              
            }
            
            .content .date-mail {
              margin-top: 15px;
              text-align: right;
            }

            .content .content__main {
              margin-top: 15px;
            }
            
            .content .assign {
              margin-top: 15px;
              width: 200px;
              padding-right: 30px;
              float: right;
              text-align:center;
              font-weight: bold;
            }
            
            .content .assign .assign__instansi {
              margin-bottom: 80px;
            }
            
            .content .assign .assign__name {
              border-bottom: 2px solid black;
            }
            
            .content .assign .assign__nik {
              font-weight: normal;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="instansi instansi--region" style="font-weight: bold; font-size: 15px;">
              <?php echo $headerConfig['instansi']['region']; ?>
            </div>
            <div class="instansi instansi--name" style="font-weight: bold;">
              <?php echo $headerConfig['instansi']['name']; ?>
            </div>
            <div class="instansi instansi--address" style="font-size: 12px;">
              <?php echo $headerConfig['instansi']['address']; ?>
            </div>
        </header>
        
        <main class="content">
          <div class="date-mail">
            <?php echo $dateMail; ?>
          </div>
          <div class="content__main">
            <?php echo $contentMain; ?>
          </div>
          
          <div class="assign">
            <div class="assign__instansi">
              <div class="instansi-name">
                <?php echo $footerConfig['assign']['instansi']['name']; ?>
              </div>
              <div class="instansi-region">
                <?php echo $footerConfig['assign']['instansi']['region']; ?>
              </div>
            </div>
            
            <div class="assign__name">
              <?php echo $footerConfig['assign']['name']; ?>
            </div>
            <div class="assign__nik">
              <?php echo $footerConfig['assign']['nik']; ?>
            </div>
          </div>
        </main>
    </body>
</html>