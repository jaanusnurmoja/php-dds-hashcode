<?php
/**
 * This is the example web application that demonstrates how to handle hashcode containers together with hashcode
 * PHP library and DigiDocService.
 *
 * The default view where user sees 2 forms. One for uploading an existing container and one for uploading a
 * datafile and creating a new container.
 *
 * PHP version 5.3+
 *
 * LICENSE:
 *
 * This library is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation;
 * either version 2.1 of the License, or (at your option) any
 * later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package       DigiDocHashcodeExample
 * @version       1.0.0
 * @author        Tarmo Kalling <tarmo.kalling@nortal.com>
 * @license       http://www.opensource.org/licenses/lgpl-license.php LGPL
 */
?>
<div class="span6">
    <form id="oldContainerForm" method="post" enctype="multipart/form-data">
        <div class="titled-container-header">
            <h1>Scenario 1: start by sending a DigiDoc/BDOC file</h1>
        </div>
        <div class="titled-container-body">
            <input name="container" type="file" accept=".bdoc,.ddoc"/>
            <br/>
            <input type="hidden" name="request_act" value="PARSE_OLD_DOCUMENT"/>
            <input type="submit" value="Send DigiDoc/BDOC file" onclick="document.oldContainerForm.submit();"/>
        </div>
    </form>
</div>
<div class="span6">
    <form id="newContainerForm" method="post" enctype="multipart/form-data">
        <div class="titled-container-header">
            <h1>Scenario 2: start by choosing signed file format and sending a datafile</h1>
        </div>
        <div class="titled-container-body">
            <input name="dataFile" type="file"/>
            <br/>
            <select name="containerType">
                <option value="BDOC 2.1">BDOC 2.1</option>
                <option value="DIGIDOC-XML 1.3">DIGIDOC-XML 1.3</option>
            </select>
            <br/>
            <br/>
            <input type="hidden" name="request_act" value="CREATE_NEW_DOCUMENT"/>
            <input type="submit" value="Send datafile" onclick="document.newContainerForm.submit();"/>
        </div>
    </form>
</div>