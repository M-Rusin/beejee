<div class="container">
    <nav class='navbar navbar-toggleable navbar-light bg-faded'>
        <a class="btn btn-primary" href="/todo/create">Добавить</a>
    </nav>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>id <span><a href='/todo/index/?sort=id&order=ASC'>↑</a>|<a href='/todo/index/?sort=id&order=DESC'>↓</a></span></th>
                        <th>имя <span><a href="/todo/index/?sort=name&order=ASC">↑</a>|<a href="/todo/index/?sort=name&order=DESC">↓</a></th>
                        <th>задача</th>
                        <th>email <span><a href="/todo/index/?sort=email&order=ASC">↑</a>|<a href="/todo/index/?sort=email&order=DESC">↓</a></th>
                        <th>статус <span><a href="/todo/index/?sort=status&order=ASC">↑</a>|<a href="/todo/index/?sort=status&order=DESC">↓</a></th>
                        <th>
                            <?php if(checkAuth()) echo 'ред.'; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data[0] as $column => $row) : ?>
                        <tr>
                            <td><?=$row['id']?></td>
                            <td><?=$row['name']?></td>
                            <td><?=$row['text']?></td>
                            <td><?=$row['email']?></td>
                            <td><?=$row['status'] ? ($row['status'] == 2 ? 'завершённая задача' : 'отредактировано администратором') : 'открытая задача'?></td>
                            <td>
                                <?php if(checkAuth()): ?>
                                    <a href='/todo/edit/?id=<?=$row["id"]?>' >править</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>
<?php if($data[1]) : ?>
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="Page navigation example">
                        <?=$data[1];?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

