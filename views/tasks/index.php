<?php

/**
 * @var array $tasks
 * @var Task $task
 * @var Paginator $paginator
 * @var string $orderDirection
 */

use components\App;
use components\Paginator;
use components\Session;
use models\Task;

?>

<?php if (Session::has('errors')): ?>
    <ul class="alert alert-danger col-12">
        <?php foreach (Session::get('errors') as $message): ?>
            <li class="ml-2"><?= $message ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (Session::has('success')): ?>
    <div class="alert alert-success col-12 text-center">
        <?= Session::get('success') ?>
    </div>
<?php endif ?>

<?php if ($tasks): ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">ID <a
                        href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'id,' . $orderDirection])) ?>"><i
                            class="fa fa-fw fa-sort"></i></a></th>
            <th scope="col">Name <a
                        href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'name,' . $orderDirection])) ?>"><i
                            class="fa fa-fw fa-sort"></i></a></th>
            <th scope="col">Email <a
                        href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'email,' . $orderDirection])) ?>"><i
                            class="fa fa-fw fa-sort"></i></a></th>
            <th scope="col">Text <a
                        href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'text,' . $orderDirection])) ?>"><i
                            class="fa fa-fw fa-sort"></i></a></th>
            <th scope="col">Completed <a
                        href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'completed,' . $orderDirection])) ?>"><i
                            class="fa fa-fw fa-sort"></i></a></th>
            <th scope="col">Edited by admin <a
                        href="/tasks?<?= http_build_query(array_merge(App::$request->all(), ['sort' => 'admin_edited,' . $orderDirection])) ?>"><i
                            class="fa fa-fw fa-sort"></i></a></th>
            <?php if (Session::has('user')): ?>
                <th scope="col">Action</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task->getAttribute('id') ?></td>
                <td><?= $task->getAttribute('name') ?></td>
                <td><?= $task->getAttribute('email') ?></td>
                <td><?= $task->getAttribute('text') ?></td>
                <td scope="col">
                    <?php for($i = 1; $i <= $task->getAttribute('completed'); $i++): ?>
                        Completed <?php if($i != $task->getAttribute('completed')): ?>,<?php endif;?>
                    <?php endfor; ?>
                </td>
                <td scope="col">
                    <?php for($i = 1; $i <= $task->getAttribute('admin_edited'); $i++): ?>
                       Edited <?php if($i != $task->getAttribute('admin_edited')): ?>,<?php endif;?>
                    <?php endfor; ?>
                </td>
                <?php if (Session::has('user')): ?>
                    <td>
                        <a class="btn btn-primary" href="/tasks/edit?id=<?= $task->getAttribute('id') ?>">Edit</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <?php if ($paginator->count() > $paginator->perPage()): ?>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $paginator->links(); $i++): ?>
                    <li class="page-item"><a class="page-link"
                                             href="?<?= http_build_query(array_merge(App::$request->all(), ['page' => $i])) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php else: ?>
    <div class="col-12 text-center">
        По запросу ничего не найдено!
    </div>
<?php endif; ?>
