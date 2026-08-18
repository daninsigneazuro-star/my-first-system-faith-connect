<?php
/** Step 3 of the program flow: admin dashboard with the four module groups. */

declare(strict_types=1);

require_once __DIR__ . '/includes/records.php';

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
$bodyClass = 'dashboard-page';

require __DIR__ . '/includes/layout/header.php';

$statistics = record_statistics();

$recent = find_records([], 5, 1, 'r.created_at DESC');

$pendingCount = $statistics['by_status']['pending'] ?? 0;

$statIcons = [
    'baptismal'    => '💧',
    'marriage'     => '💍',
    'confirmation' => '✝',
    'death'        => '🪦',
];

$summaryIcons = [
    'total'    => '👥',
    'pending'  => '⏳',
    'verified' => '✔',
    'archived' => '📦',
];
?>

<div class="stat-grid">
    <?php foreach (registries() as $type => $definition): ?>
        <a class="stat stat-<?= e($type) ?> teal" data-icon="<?= e($statIcons[$type] ?? '•') ?>" href="<?= e(url('registry/index.php?type=' . $type)) ?>">
            <span><?= e($definition['short']) ?> records</span>
            <strong><?= (int) ($statistics['by_type'][$type] ?? 0) ?></strong>
        </a>
    <?php endforeach; ?>
</div>

<div class="stat-grid">
    <a class="stat stat-total" data-icon="<?= e($summaryIcons['total']) ?>" href="<?= e(url('records/index.php')) ?>">
        <span>Total active records</span>
        <strong><?= (int) $statistics['total'] ?></strong>
    </a>
    <a class="stat stat-pending orange" data-icon="<?= e($summaryIcons['pending']) ?>" href="<?= e(url('records/index.php?status=pending')) ?>">
        <span>Pending verification</span>
        <strong><?= (int) $pendingCount ?></strong>
    </a>
    <a class="stat stat-verified green" data-icon="<?= e($summaryIcons['verified']) ?>" href="<?= e(url('records/index.php?status=verified')) ?>">
        <span>Verified records</span>
        <strong><?= (int) ($statistics['by_status']['verified'] ?? 0) ?></strong>
    </a>
    <a class="stat stat-archived" data-icon="<?= e($summaryIcons['archived']) ?>" href="<?= e(url('records/index.php?archived=1')) ?>">
        <span>Archived records</span>
        <strong><?= (int) $statistics['archived'] ?></strong>
    </a>
</div>

<div class="card">
    <h2>Recently encoded records</h2>
    <?php if ($recent['rows'] === []): ?>
        <p class="empty">No records yet. Start by adding an entry from any registry.</p>
    <?php else: ?>
        <table class="data">
            <thead>
            <tr>
                <th>Registry</th>
                <th>Name</th>
                <th>Date of event</th>
                <th>Status</th>
                <th>Encoded by</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($recent['rows'] as $row): ?>
                <tr>
                    <td><?= e(registries()[$row['registry_type']]['short']) ?></td>
                    <td><?= e($row['subject_name']) ?><?= $row['bride_name'] ? ' &amp; ' . e($row['bride_name']) : '' ?></td>
                    <td><?= e(format_date($row['event_date'])) ?></td>
                    <td><?= status_badge($row['status']) ?></td>
                    <td><?= e($row['created_by_name'] ?? '—') ?></td>
                    <td class="actions">
                        <a class="btn btn-sm btn-light" href="<?= e(url('registry/view.php?id=' . $row['id'])) ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Quick actions</h2>
    <?php foreach (registries() as $type => $definition): ?>
        <a class="btn btn-teal" href="<?= e(url('registry/form.php?type=' . $type)) ?>">Add <?= e($definition['short']) ?></a>
    <?php endforeach; ?>
    <a class="btn" href="<?= e(url('reports/index.php')) ?>">Reports &amp; summary</a>
    <?php if (can('user.manage')): ?>
        <a class="btn btn-light" href="<?= e(url('admin/users.php')) ?>">Manage users</a>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/layout/footer.php'; ?>
