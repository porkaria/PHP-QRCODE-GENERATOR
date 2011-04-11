<?php

/**
 * Image_QRCode - QR 2D barcode generator
 *
 * Image_QRCode - QR 2D barcode generator
 *
 * PHP version 5
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library in the file LICENSE.LGPL; if not, write to the
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307 USA
 *
 * @category  Images
 * @package   Image_QRCode
 * @author    Y.Swetake <swe@venus.dti.ne.jp>
 * @author    Rich Sage <rich.sage@gmail.com>
 * @copyright 2009 Y.Swetake
 * @copyright 2009 R.Sage
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   SVN: $Id$
 * @link      http://code.google.com/p/pearqrcode/
 */

/**
 * Base class for exceptions in PEAR
 */

require_once 'PEAR/Exception.php';

/**
 * Image_QRCode_Exception
 *
 * @category Image
 * @package  Image_QRCode
 * @author   Y.Swetake <swe@venus.dti.ne.jp>
 * @author   Rich Sage <rich.sage@gmail.com>
 * @license  http://www.gnu.org/licenses/lgpl-2.1.txt GNU LGPL
 * @version  Release: 0.1
 * @link     http://code.google.com/p/pearqrcode/
 */

class Image_QRCode_Exception extends PEAR_Exception
{
}
?>
