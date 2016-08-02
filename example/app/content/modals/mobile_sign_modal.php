<?php
/**
 * This is the example web application that demonstrates how to handle hashcode containers together with hashcode
 * PHP library and DigiDocService.
 *
 * The modal that shows the form for adding parameters to sign with Mobile ID.
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
<div class="modal fade" id="mobileSignModal" tabindex="-1" role="dialog" aria-labelledby="mobileSignModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="mobileSignModalHeader">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="mobileSignModalLabel">Sign the document with Mobile ID</h4>
            </div>
            <div class="modal-body">
                <div class="mobileSignModalContent">
                    <div id="mobileSignErrorContainer" style="display: none;" class="alert alert-danger"></div>
                    <table>
                        <tr>
                            <td>
                                <label for="mid_PhoneNumber">Mobilephone number:</label>
                            </td>
                            <td>
                                <input id="mid_PhoneNumber" type="text"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="mid_idCode">Social security number:</label>
                            </td>
                            <td>
                                <input id="mid_idCode" type="text"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer" id="mobileSignModalFooter">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="ee.sk.hashcode.StartMobileSign();">
                    Start signing process
                </button>
            </div>
        </div>
    </div>
</div>