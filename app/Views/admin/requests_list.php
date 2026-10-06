<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="va-page-intro"><div><h2>Property request inbox</h2><p>Every website enquiry is stored here before the customer continues to WhatsApp.</p></div></div>

<section class="va-card">
    <div class="va-card-head">
        <form class="va-filters" action="<?= base_url('admin/requests') ?>" method="get">
            <div class="va-field"><label>Search</label><input type="search" name="q" value="<?= esc($q) ?>" placeholder="Name, phone, reference"></div>
            <div class="va-field"><label>Status</label><select name="status"><option value="">All statuses</option><?php foreach (['new','contacted','scheduled','closed'] as $status): ?><option value="<?= $status ?>" <?= $currentStatus === $status ? 'selected' : '' ?>><?= ucfirst($status) ?></option><?php endforeach; ?></select></div>
            <button class="va-btn va-btn--dark" type="submit"><i class="fa-solid fa-filter"></i> Apply</button>
            <a class="va-btn va-btn--light" href="<?= base_url('admin/requests') ?>">Clear</a>
        </form>
    </div>
    <div class="va-table-wrap">
        <table class="va-table">
            <thead><tr><th>Request</th><th>Customer</th><th>Property</th><th>Message</th><th>Status</th><th>Received</th></tr></thead>
            <tbody>
            <?php if (empty($requests)): ?>
                <tr><td colspan="6"><div class="va-empty">No requests found.</div></td></tr>
            <?php else: ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><strong><?= esc($request->request_reference) ?></strong><div class="va-help"><?= esc(ucwords(str_replace('_', ' ', $request->request_type))) ?></div></td>
                        <td><strong><?= esc($request->full_name) ?></strong><div class="va-help"><a href="tel:<?= esc(clean_phone_digits($request->phone)) ?>"><?= esc($request->phone) ?></a><?php if ($request->email): ?> · <a href="mailto:<?= esc($request->email) ?>"><?= esc($request->email) ?></a><?php endif; ?></div></td>
                        <td><a href="<?= base_url('property/' . $request->property_slug) ?>" target="_blank"><strong><?= esc($request->property_title ?? 'Property') ?></strong></a><?php if ($request->preferred_date): ?><div class="va-help"><i class="fa-regular fa-calendar"></i> <?= esc(date('j M Y', strtotime($request->preferred_date))) ?></div><?php endif; ?></td>
                        <td><div class="va-request-msg" title="<?= esc($request->message) ?>"><?= esc($request->message ?: '—') ?></div></td>
                        <td>
                            <form class="va-status-form" method="post" action="<?= base_url('admin/requests/status/' . $request->id) ?>">
                                <?= csrf_field() ?>
                                <select name="status" aria-label="Request status"><?php foreach (['new','contacted','scheduled','closed'] as $status): ?><option value="<?= $status ?>" <?= $request->status === $status ? 'selected' : '' ?>><?= ucfirst($status) ?></option><?php endforeach; ?></select>
                                <button class="va-btn va-btn--light va-btn--sm" type="submit">Save</button>
                            </form>
                        </td>
                        <td><?= esc(date('j M Y', strtotime($request->created_at))) ?><div class="va-help"><?= esc(date('H:i', strtotime($request->created_at))) ?></div></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= $pager->links('default', 'admin_pager') ?>
</section>

<?= $this->endSection() ?>
