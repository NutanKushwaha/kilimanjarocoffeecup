<header class="panel-heading">
    <div class="row">        
        <div class="col-sm-10">
            <h3 style="margin: 0; text-align: center">Assigned Company Departments</h3>
        </div>
        <?php
        /*
            <div class="col-sm-1" style="text-align: right">
                <a href="company/add"><h3 style="cursor: pointer; margin: 0; color: #000"><i class="fa fa-plus-square" title="Add New user"></i></h3></a>
            </div>
        */
        ?>
    </div>
</header>
<div class="col-lg-12 padding-0" style="padding-top: 15px;">
    <?php 
        echo ol($depts, [ 'class' => 'simple-order-list',  'style' => 'list-sytle:inherit' ])
    ?>
</div>
