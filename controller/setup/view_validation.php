<?php


//validates the view
if (validateAdminPage()) {
    include(VIEW_ADMIN); //Access to the Admin view
} else if (validatePage()) {
    include(VIEW_CLIENT); //Access to the client view
} else {
    include(START_PAGE); //Access to default view
}


?>