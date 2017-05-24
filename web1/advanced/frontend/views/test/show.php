<html>

<table>
    <?php foreach($data as $k=>$v){?>
    <tr>
        <td>
            <?php echo $v['book_id']?>
        </td>
        <td>
            <a href="?r=test/del&id=<?php echo $v['book_id']?>">删除</a>
        </td>
    </tr>
<?php }?>
</table>

</html>