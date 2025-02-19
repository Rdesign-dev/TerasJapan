<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Email</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/changeemail.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/footer.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var baseUrl = '<?php echo base_url(); ?>';
    </script>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="back-btn" onclick="history.back()">&larr;</div>
        <div class="title">CHANGE EMAIL ADDRESS</div>
    </div>

    <!-- Email Change Form -->
    <div class="email-container">
        <p class="email-description">Edit your email address using the form below. You'll be required to verify your email address.</p>
        
        <form id="changeEmailForm">
            <div class="form-group">
                <input type="email" id="new_email" name="new_email" placeholder="Enter your email address" required>
            </div>
            <button type="button" class="verify-btn" id="sendOtpBtn">VERIFY EMAIL</button>
        </form>

        <form id="verifyOtpForm" style="display: none;">
            <div class="form-group">
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
            </div>
            <button type="submit" class="save-btn">VERIFY OTP</button>
        </form>
    </div>

    <div id="rewardRedeemPopup" class="popup-referral" style="display: none;">
        <div class="popup-content">
            <span class="close-btn" onclick="closeRewardRedeemPopup()">&times;</span>
            <p id="rewardRedeemMessage"></p>
            <div class="button-container">
                <div class="rectangle ok-btn" onclick="closeRewardRedeemPopup()">
                    <p class="text">OK</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#sendOtpBtn').click(function() {
                var newEmail = $('#new_email').val();
                if (newEmail) {
                    $.ajax({
                        url: '<?php echo base_url("changeemail/request_change_email"); ?>',
                        type: 'POST',
                        data: { new_email: newEmail },
                        dataType: 'json',
                        success: function(response) {
                            showRewardRedeemPopup(response.message);
                            if (response.status === 'success') {
                                $('#changeEmailForm').hide();
                                $('#verifyOtpForm').show();
                            }
                        },
                        error: function() {
                            showRewardRedeemPopup('An error occurred. Please try again.');
                        }
                    });
                }
            });

            $('#verifyOtpForm').submit(function(e) {
                e.preventDefault();
                var otp = $('#otp').val();
                $.ajax({
                    url: '<?php echo base_url("changeemail/verify_otp"); ?>',
                    type: 'POST',
                    data: { otp: otp },
                    dataType: 'json',
                    success: function(response) {
                        showRewardRedeemPopup(response.message);
                        if (response.status === 'success') {
                            location.reload();
                        }
                    },
                    error: function() {
                        showRewardRedeemPopup('An error occurred. Please try again.');
                    }
                });
            });
        });

        function showRewardRedeemPopup(message) {
            document.getElementById('rewardRedeemMessage').innerText = message;
            document.getElementById('rewardRedeemPopup').style.display = 'flex';
        }

        function closeRewardRedeemPopup() {
            document.getElementById('rewardRedeemPopup').style.display = 'none';
        }
    </script>
</body>
<?php include 'application/views/layout/Footer.php'; ?>
</html>
