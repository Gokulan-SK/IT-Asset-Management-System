<?php include BASE_PATH . "views/layouts/components/quick-access.php"; ?>
<div class="content-frame">
    <h3><?= $pageTitle ?? "Check-In Asset" ?></h3>

    <?php
    $ledgerEntry = $ledgerEntry ?? null;
    $successMessage = $successMessage ?? null;
    $errorMessage = $errorMessage ?? null;
    ?>

    <?php if (isset($successMessage)): ?>
        <div class="alert success">
            <span class="closebtn">&times;</span>
            <p><?= htmlspecialchars($successMessage); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <div class="alert error">
            <span class="closebtn">&times;</span>
            <p><?= htmlspecialchars($errorMessage); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($ledgerEntry): ?>
        <form action="check-in" method="POST" class="form">
            <input type="hidden" name="ledger-id" value="<?= htmlspecialchars($ledgerEntry['ledger_id']) ?>" />

            <div class="label-input">
                <label for="asset-name">Asset</label>
                <input type="text" id="asset-name" value="<?= htmlspecialchars($ledgerEntry['asset_name']) ?>" disabled />
            </div>

            <div class="label-input">
                <label for="employee-name">Employee</label>
                <input type="text" id="employee-name" value="<?= htmlspecialchars($ledgerEntry['employee_name']) ?>"
                    disabled />
            </div>

            <div class="label-input">
                <label for="checkout-date">Check-out Date</label>
                <input type="date" id="checkout-date" value="<?= htmlspecialchars($ledgerEntry['check_out_date']) ?>"
                    disabled />
            </div>

            <div class="label-input">
                <label for="checkin-date">Check-in Date</label>
                <input type="date" id="checkin-date" name="checkin-date" required />
            </div>

            <div class="label-input">
                <label for="comments">Comments</label>
                <textarea name="comments" id="comments" rows="4"
                    placeholder="Any return notes..."><?= htmlspecialchars($ledgerEntry['comments'] ?? '') ?></textarea>
            </div>

            <div class="submit-reset">
                <button type="reset">Clear</button>
                <button type="submit" class="btn-primary">Confirm Check-in</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert error">
            <p>Unable to load asset check-in data.</p>
        </div>
    <?php endif; ?>
</div>