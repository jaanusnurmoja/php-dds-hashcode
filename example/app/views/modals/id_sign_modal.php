<div class="modal fade" id="idSignModal" tabindex="-1" role="dialog" aria-labelledby="idSignModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="idSignModalHeader">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="idSignModalLabel">Sign the document with ID Card</h4>
            </div>
            <div class="modal-body">
                <div class="idSignModalContent">
                    <div class="alert alert-danger" id="idSignModalErrorContainer" style="display: none;"></div>
                    <table>
                        <tr>
                            <td><label for="idSignCity">City:</label></td>
                            <td><input id="idSignCity" type="text"/></td>
                        </tr>
                        <tr>
                            <td><label for="idSignState">State:</label></td>
                            <td><input id="idSignState" type="text"/></td>
                        </tr>
                        <tr>
                            <td><label for="idSignPostalCode">Postal Code:</label></td>
                            <td><input id="idSignPostalCode" type="text"/></td>
                        </tr>
                        <tr>
                            <td><label for="idSignCountry">Country:</label></td>
                            <td><input id="idSignCountry" type="text"/></td>
                        </tr>
                        <tr>
                            <td><label for="idSignRole">Role:</label></td>
                            <td><textarea id="idSignRole" cols="30" rows="10"></textarea></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer" id="idSignModalFooter">
                <?php printf('<input type="hidden" name="_token" value="%s">',
                    htmlspecialchars($csrfSigner->getSignature())); ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="ee.sk.hashcode.IDCardSign()">Sign the document
                </button>
            </div>
        </div>
    </div>
</div>