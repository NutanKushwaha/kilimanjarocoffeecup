<script src="<?= base_url(); ?>js/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url(); ?>js/datatables/dataTables.bootstrap.js"></script>
<script>
    $(function() {
        $("#<?= $table_id; ?>").dataTable();
    });
</script>