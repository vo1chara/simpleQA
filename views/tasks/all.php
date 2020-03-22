<div class="container">
    <table>
        <tr>
            <th>вопрос</th>
            <th>ответ</th>
            <th>правильный ответ</th>
            <th>правильно?</th>
            <th>Пользователь(id)</th>
        </tr>
        <?php if (!empty($tasks)) { ?>
            <?php foreach ($tasks as $task) : ?>
                <tr>
                    <td><?= $task['question'] ?></td>
                    <td><?= $task['answer'] ?></td>
                    <td><?= $task['right_answer'] ?></td>
                    <td><?= $task['result'] ?></td>
                    <td><?= $task['id_user'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php } ?>

    </table>