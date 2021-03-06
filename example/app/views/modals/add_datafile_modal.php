<div class="modal fade" id="addDatafileModal" tabindex="-1" role="dialog" aria-labelledby="addDatafileModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="addDatafileModalLabel">Add a new datafile to container.</h4>
            </div>
            <form id="addDatafileForm" method="post" enctype="multipart/form-data">
                <?php printf('<input type="hidden" name="_token" value="%s">',
                    htmlspecialchars($csrfSigner->getSignature())); ?>
                <div class="modal-body">
                    <input name="dataFile" type="file"/>
                    <input type="hidden" name="request_act" value="ADD_DATAFILE"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" onclick="document.addDatafileForm.submit();"
                           value="Add datafile"/>
                </div>
            </form>
        </div>
    </div>
</div>