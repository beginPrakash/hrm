<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title"><?php echo ucfirst($title); ?></h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active"><?php echo ucfirst($title); ?></li>
            </ul>
        </div>
        <?php if(!isset($breadButton)){ ?>
        <div class="col-auto float-end ms-auto">
            <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_Form"><i class="fa fa-plus"></i> Add <?php echo ucfirst($title); ?></a>
        </div>
        <?php } ?>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/includes/breadcrumbs.blade.php ENDPATH**/ ?>