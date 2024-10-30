<div class="container-fluid">
    <h1 class="imk-admin-header">IMK Apps List</h1>
    <div class="thirt-party-apps-list">
        <div class="row">
            <?php
                foreach ($third_parties_apps as $app){
                    ?>

                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="app-block">
                            <div class="app-image-container" >
                                <img  src="<?php echo $app['image'] ?>" class="btn-block" >
                            </div>
                            <div class="app-info text-center">
                                <h4 class="app-title"><?php echo $app['title'] ?></h4>
                                <a class="btn btn-default" href="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>&app=<?php echo $app['slug'] ?>"> Install Now </a>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            ?>
        </div>
    </div>
</div>