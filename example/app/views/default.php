<div class="col-sm-6">
    <form id="oldContainerForm" class="form-inline" method="post" enctype="multipart/form-data">
        <div class="titled-container-header">
            <h1>Scenario 1: start by sending a DigiDoc/BDOC file</h1>
        </div>
        <div class="titled-container-body">
            <div class="row">
                <label for="choose-container-file">Choose BDOC/DDOC container:</label>
                <input name="container" class="form-control" type="file" accept=".bdoc,.ddoc"/>
            </div>
            <div class="row row-buffer">
                <div class="form-group">
                    <?php printf('<input type="hidden" name="_token" value="%s">',
                        htmlspecialchars($csrfSigner->getSignature())); ?>
                    <input type="hidden" name="request_act" value="PARSE_OLD_DOCUMENT"/>
                    <input type="submit" class="btn btn-default" value="Send DigiDoc/BDOC file"
                           onclick="document.oldContainerForm.submit();"/>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="col-sm-6">
    <form id="newContainerForm" class="form-inline" method="post" enctype="multipart/form-data">
        <div class="titled-container-header">
            <h1>Scenario 2: start by choosing signed file format and sending a datafile</h1>
        </div>
        <div class="titled-container-body">
            <div class="row">
                <div class="form-group col-lg-offset-1">
                    <label for="choose-data-file">Choose data file: </label>
                    <input class="form-control" name="dataFile" type="file"/>
                </div>
            </div>
            <div class="row row-buffer">
                <div class="form-group col-lg-offset-1">
                    <label for="container-type">Container type:</label>
                    <select name="containerType" id="container-type" class="form-control">
                        <option value="BDOC 2.1">BDOC 2.1</option>
                        <option value="DIGIDOC-XML 1.3">DIGIDOC-XML 1.3</option>
                    </select>
                    <?php printf('<input type="hidden" name="_token" value="%s">',
                        htmlspecialchars($csrfSigner->getSignature())); ?>
                    <input type="hidden" name="request_act" value="CREATE_NEW_DOCUMENT"/>
                    <input class="btn btn-default" type="submit" value="Send datafile"
                           onclick="document.newContainerForm.submit();"/>
                </div>
            </div>
        </div>
    </form>
</div>