<?php use Framework\Classes\Lang; ?>

<button class="add-button" onclick="window.location.href = '/users/add'"><?= '+ ' . Lang::get('app.list.add_button') ?></button>

<?php if ($users): ?>
    <table>
        <thead>
            <tr>
                <th><?= Lang::get('app.list.header_name') ?></th>
                <th><?= Lang::get('app.list.header_username') ?></th>
                <th><?= Lang::get('app.list.header_email') ?></th>
                <th><?= Lang::get('app.list.header_address') ?></th>
                <th><?= Lang::get('app.list.header_phone') ?></th>
                <th><?= Lang::get('app.list.header_company') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->name; ?></td>
                    <td><?= $user->username; ?></td>
                    <td><?= $user->email; ?></td>
                    <td><?= $user->address['street'] . ', ' . $user->address['zipcode'] . ' ' . $user->address['city']; ?></td>
                    <td><?= $user->phone; ?></td>
                    <td><?= $user->company['name']; ?></td>
                    <td class="buttons">
                        <button class="action-button" onclick="window.location.href = '/users/edit/<?= $user->id ?>'""><?= Lang::get('app.list.edit_button') ?></button>
                        <button class="action-button" onclick="showConfirmationDialog('<?= $user->id ?>')"><?= Lang::get('app.list.remove_button') ?></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>

<div class="modal-overlay"></div>

<div id="confirmation-dialog">
    <p><?= Lang::get('app.list.remove_confirmation_question') ?></p>
    <div class="buttons-container">
        <button class="action-button" onclick="closeConfirmationDialog()"><?= Lang::get('app.list.remove_confirmation_button_cancel') ?></button>
        <button class="action-button" onclick="confirmDelete()"><?= Lang::get('app.list.remove_confirmation_button_ok') ?></button>
    </div>
</div>
