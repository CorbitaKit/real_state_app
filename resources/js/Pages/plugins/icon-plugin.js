import { addIcons } from "oh-vue-icons";
import { FaArrowRight, FaChartLine, FaClipboard, FaFileAlt, FaLandmark, FaReceipt, FaTasks, FaUserFriends, FaWindowClose, FcApproval } from "oh-vue-icons/icons";
import { FaArrowLeft } from "oh-vue-icons/icons";
import { FaArchive } from "oh-vue-icons/icons";
import { FaListAlt } from "oh-vue-icons/icons";
import { FaUsers } from "oh-vue-icons/icons";
import { FaAlignJustify } from "oh-vue-icons/icons";
import { FaBell } from "oh-vue-icons/icons";
import { FaEnvelope } from "oh-vue-icons/icons";


const iconPlugin = addIcons(
    FaArrowRight, 
    FaArrowLeft, 
    FaArchive, 
    FaListAlt, 
    FaUsers, 
    FaAlignJustify, 
    FaBell, 
    FaEnvelope, 
    FaUserFriends,
    FaTasks,
    FaLandmark,
    FaReceipt,
    FaChartLine,
    FaClipboard,
    FaFileAlt,
    FcApproval,
    FaWindowClose
)

export default {
    iconPlugin
}