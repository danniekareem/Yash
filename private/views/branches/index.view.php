<?php $this->view('includes/header'); ?>
<?php $this->view('includes/navbar'); ?>

<div class="d-flex">
    <?php $this->view('includes/sidebar'); ?>

    <div class="flex-grow-1 p-4 bg-light min-vh-100">

        <div class="d-flex justify-content-between mb-4">
            <div>
                <h3 class="fw-bold">Branches</h3>
                <small class="text-muted">Manage company branches</small>
            </div>
            <a href="<?= ROOT ?>/branches/add"
                class="btn btn-sm btn-outline-primary px-2 py-1"
                style="font-size: 0.85rem;">
                <i class="bi bi-plus-circle me-1"></i> Add Branch
            </a>

        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($branches as $b): ?>
                            <tr>
                                <td><?= esc($b->name) ?></td>
                                <td>
                                    <span class="badge bg-<?= $b->status === 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($b->status) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= ROOT ?>/branches/edit/<?= $b->id ?>" class="btn btn-sm btn-outline-primary">
                                        ✏️ Edit
                                    </a>
                                    <a href="<?= ROOT ?>/branches/toggle/<?= $b->id ?>"
                                        class="btn btn-sm btn-outline-warning">
                                        🔄 Deactivate
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php $this->view('includes/footer'); ?>