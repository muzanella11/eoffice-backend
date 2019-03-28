<table class="content__table">
    <tr class="theadMe">
        <td class="td" style="width: 50%; padding: 10px 10px;">
            Nama
        </td>
        <td class="td" style="width: 50%; padding: 10px 10px;">
            Description
        </td>
    </tr>

    <?php
        if (count($contentTable) > 0) {
            foreach ($contentTable as $key => $value) {
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['name']) ? $value['name'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['description']) ? $value['description'] : '-'; ?>
        </td>
    </tr>
    <?php
            } 
        } else { 
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 100%;">
            Tidak ada data ditemukan
        </td>
    </tr>
    <?php } ?>
</table>
