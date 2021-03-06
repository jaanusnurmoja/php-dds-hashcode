<?php
/**
 * This is the example web application that demonstrates how to handle hashcode containers together with hashcode
 * PHP library and DigiDocService.
 *
 * Footer of the example web application template
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
</div>

<div class="row footer-row">
    <?php
    $version = SK\Digidoc\Digidoc::version();
    $filename = "dds-hashcode-$version.tar.gz";
    $updated = file_exists($filename) ? date("d.m.Y", filemtime("$filename")) : "N/A";
    ?>
    <p><a href="<?php echo $filename ?>">Source code</a> (updated <?php echo $updated ?>). Hashcode library
        version <?php echo $version ?>.</p>
</div>
</div>

</body>
</html>