<div class="modal fade" id="mobileSignModal" tabindex="-1" role="dialog" aria-labelledby="mobileSignModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="mobileSignModalHeader">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="mobileSignModalLabel">Sign the document with Mobile ID</h4>
            </div>
            <div class="modal-body">
                <div class="mobileSignModalContent">
                    <div id="mobileSignErrorContainer" style="display: none;" class="alert alert-danger"></div>
                    <div class="row ">
                        <div class="col-md-12">
                           <p class="alert alert-info">
                              <strong>NOTE:</strong> Phone number must start with country prefix. <br />
                              Example: <strong>+37212345678</strong>
                           </p>
                        </div>
                    </div>
                    <table>
                        <tr>
                            <td><label for="mid_PhoneNumber">Mobile phone number:</label></td>
                            <td><input id="mid_PhoneNumber" type="text"/></td>
                        </tr>
                        <tr>
                            <td><label for="mid_idCode">Social security number:</label></td>
                            <td><input id="mid_idCode" type="text"/></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer" id="mobileSignModalFooter">
                <?php printf('<input type="hidden" name="_token" value="%s">',
                    htmlspecialchars($csrfSigner->getSignature())); ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="ee.sk.hashcode.StartMobileSign()">
                    Start signing process
                </button>
            </div>
        </div>
    </div>
</div>