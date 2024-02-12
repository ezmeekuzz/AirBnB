
            <!-- begin footer -->
            <footer class="footer">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-left">
                        <p>&copy; Copyright <?=date('Y');?>. All rights reserved.</p>
                    </div>
                </div>
            </footer>
            <!-- end footer -->
        </div>
        <!-- end app-wrap -->
    </div>
    <!-- end app -->
    <div id="loading">
        <img id="loading-image" src="<?=base_url();?>assets_admin/img/loader.gif" alt="Loading..." />
    </div>

    <!-- plugins -->
    <script src="<?=base_url();?>assets_admin/js/vendors.js"></script>

    <!-- custom app -->
    <script src="<?=base_url();?>assets_admin/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
    <script src="<?=base_url();?>assets_admin/js/Toolbar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://unpkg.com/cropperjs"></script>
    <script>
        $(".chosen-select").chosen();
    </script>
</body>
</html>