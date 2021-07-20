/**
 *
 * The current script will handle the application functionality
 * by executing needed functions for each page
 *
 * pageIdentifier----------------- object that is used to determine which page is currently active
 * windowLoadHandler------------------- function that load page functionality by the current active page
 *
 */

(function ($) {


    var pageIdentifier = {
        auth:'#auth-page',
        home : '#home-page',
        cake:'#cake-page',
        baking:'#baking-page',
        errorPage:'#error-page',
        maker:'#cake-maker-page'
    },/**MessageModal */msgModal,/**Loader */ loader


    // $(window).on('resize', functions.windowResizeHandler);
    /**
     * Initialize app events
     */
    $(window).on("load",windowLoadHandler);

    /**
     * Window load handler
     */
    function windowLoadHandler(){
        var {auth, home, cake, errorPage, maker} = pageIdentifier
        if(!($(errorPage).length)) {
            $(this).on('resize', functions.windowResizeHandler);
            msgModal = new functions.MessageModal()
            loader = new functions.Loader()
            functions.initSearchForm()
            functions.initNav()
            functions.initDrawerBtns()
        }


        if(window.isLoggedIn){
            functions.initAvatar()
            new functions.UpdateProfileModal({msgModal,loader}).init()
        }

        if($(auth).length){
            new functions.Auth({msgModal,loader}).init()
        }

        if($(home).length){
            functions.getTagColorsFromJson(loader,msgModal)
            new functions.Rating({msgModal,loader}).init()
            functions.initCardsFlipperAddon()
        }

        if($(cake).length){
            new functions.CommentsController({msgModal,loader}).init()
            new functions.cakeEdit({msgModal,loader}).init()
            new functions.Rating({msgModal,loader}).init()
        }

        if($(maker).length){
            new functions.CakeMaker({msgModal,loader}).init()
        }
        if(!($(errorPage).length)) {
            loader.stopLoader();
        }

    }


})(jQuery);
