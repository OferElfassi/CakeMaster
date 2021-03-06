/**
 *
 * The current script will output the necessary
 * functions and classes to handle the application functionality
 *
 * MessageModal----------------- handling popup messages
 * initAvatar------------------- initiating avatar initials and color by specified user name
 * initCardsFlipperAddon-------- initiate card flipping jquery plugin
 * initSearchForm--------------- add form validation to search bar
 * windowResizeHandler---------- handle objects placement while window is resized
 * initNav---------------------- attach event listeners to handle responsive navigation buttons
 * initDrawerBtns--------------- attach event listeners to handle responsive drawer buttons
 * getTagColorsFromJson--------- handle ajax request to get tag color codes from json file
 * Rating----------------------- initiate star rating jquery plugin
 * commentsController----------- initiate Comments jquery plugin and preform ajax request to save and retrieve messages
 * UpdateProfileModal----------- handling update profile modal operation and requests
 * cakeEdit--------------------- handle cake edit page functionality and edit requests
 * CakeMaker-------------------- handle cake maker page functionality and requests to external rest api's
 * Auth------------------------- handle authentication process for login and signup
 * Loader----------------------- provide loader start stop functionality
 *
 */

/**
 app functions
 @typedef {Object} appFunctions
 @property {MessageModal}  MessageModal handling popup messages.
 @property {initAvatar} initAvatar  initiating avatar initials and color by specified user name.
 @property {initCardsFlipperAddon} initCardsFlipperAddon  initiate card flipping jquery plugin.
 @property {initSearchForm} initSearchForm  add form validation to search bar.
 @property {windowResizeHandler} windowResizeHandler  handle objects placement while window is resized.
 @property {initNav} initNav  attach event listeners to handle responsive navigation buttons.
 @property {initDrawerBtns} initDrawerBtns  attach event listeners to handle responsive drawer buttons.
 @property {getTagColorsFromJson} getTagColorsFromJson handle ajax request to get tag color codes from json file.
 @property {Rating} Rating  initiate star rating jquery plugin.
 @property {CommentsController} CommentsController  initiate Comments jquery plugin and preform ajax request to save and retrieve messages.
 @property {UpdateProfileModal} UpdateProfileModal  handling update profile modal operation and requests.
 @property {cakeEdit} cakeEdit  handle cake edit page functionality and edit requests.
 @property {CakeMaker} CakeMaker  handle cake maker page functionality and requests to external rest api's.
 @property {Auth} Auth  handle authentication process for login and signup.
 @property {Loader} Loader  provide loader start stop functionality.
 */


/**
 * functions - create functions and classes to handle the application functionality
 * @return {appFunctions}.
 */
var functions = (function ($) {

    /** class MessageModal Handling popup messages. */
    class MessageModal {

        /**
         * Initialize DOM elements references.
         */
        constructor() {
            var modalElement = $('#message-modal')
            this.modalElements = {
                modal: modalElement,
                overlay: $('#modal-overlay'),
                okBtn: $(modalElement).find('#modal-ok'),
                cancelBtn: $(modalElement).find('#modal-cancel'),
                exitBtn: $(modalElement).find('#close-message-modal-btn'),
                modalTitle: $(modalElement).find('.modal-header h3'),
                modalContent: $(modalElement).find('.modal-body p'),
            };
        }

        /**
         modalData definition
         @typedef {Object} modalData
         @property {string} title .
         @property {string} text .
         @property {string} color .
         @property {function} [onExit] .
         @property {function} [onOk] .
         @property {function} [onCancel] .
         @property {string} [okText] .
         @property {string} [cancelText] .
         @property {boolean} [showExitBtn] .
         @property {number} [timeout] .
         @property {function} [onTimeout] .
         */
        /**
         * Open message modal
         @param {modalData} modalData .
         */
        showMessageModal = (modalData) => {


            const {
                title, text, color, onExit = null, onOk = null, onCancel = null,
                okText = "ok", cancelText = "cancel", showExitBtn = true, timeout = 0, onTimeout = null
            } = modalData
            let {modal, overlay, okBtn, cancelBtn, exitBtn, modalTitle, modalContent} = this.modalElements


            overlay.css({display: "block"})
            modal.attr('class', `modal modal-${color}`);
            modal.css({display: "block"})


            modalTitle.html(title);
            $(modalContent).html(text);

            okBtn.html(okText)
            okBtn.css({display: onOk ? "block" : "none"})
            okBtn.on("click", onOk)

            cancelBtn.html(cancelText)
            cancelBtn.css({display: onCancel ? "block" : "none"})
            cancelBtn.on("click", onCancel)

            exitBtn.css({display: showExitBtn ? "block" : "none"})
            exitBtn.on("click", onCancel)
            if (onCancel) {
                cancelBtn.on("click", () => {
                    onCancel()
                    this.closeMessageModal()
                })
            }
            if (onExit) {
                exitBtn.on("click", () => {
                    onExit()
                    closeMessageModal()
                })
            } else {
                exitBtn.on("click", () => this.closeMessageModal())
            }
            if (timeout > 0) {
                setTimeout(() => {
                    onTimeout && onTimeout()
                    this.closeMessageModal()
                }, timeout)
            }
        }

        /**
         * Close message modal and remove event listeners
         */
        closeMessageModal = () => {
            const {modal, overlay, okBtn, cancelBtn, exitBtn} = this.modalElements
            overlay.css({display: "none"})
            modal.css({display: "none"})

        }
    }

    /** Class Loader creates spinning loader element. */
    class Loader {

        /**
         * Initialize DOM elements references.
         */
        constructor() {
            this.loader = $("#loader");
            this.overlay = $('#modal-overlay');
        }

        /**
         * Show loader.
         */
        startLoader = () => {
            this.loader.css({display: "block"})
            this.overlay.css({display: "block"})
        }

        /**
         * Hide loader.
         */
        stopLoader = () => {
            this.loader.css({display: "none"})
            this.overlay.css({display: "none"})
        }
    }

    /**
     objects
     @typedef {Object} objects
     @property {MessageModal} [msgModal] .
     @property {Loader} [loader] .
     */

    /** Class CommentsController Initiate Comments jquery plugin and preform ajax requestS to save and retrieve messages. */
    class CommentsController {

        /**
         * Initialize DOM elements references.
         * @param {objects} objects - object containing MessageModal instance.
         */
        constructor(objects) {
            this.msgModal = objects.msgModal
            this.loader = objects.loader
            this.commentContainer = $('#comments-container')

        }

        /**
         * Init the jquery comments plugin.
         */
        init = () => {

            //init comments plugin
            this.commentContainer.comments({
                enableReplying: false,
                enableUpvoting: false,
                enableNavigation: false,
                roundProfilePictures: true,
                // enableAttachments: true,
                enableHashtags: true,
                postCommentOnEnter: true,
                youText: this.commentContainer.data("userid") === -1 ? "v c" : this.commentContainer.data("username"),
                readOnly: this.commentContainer.data("userid") === -1,
                fieldMappings: {
                    id: 'id',
                    created: 'createdAt',
                    modified: 'updatedAt',
                    content: 'content',
                    creator: 'creator',
                    fullname: 'fullname',
                    createdByCurrentUser: 'created_by_current_user',
                },

                getComments: this.#commentsGetHandler,
                postComment: this.#commentsPostHandler,
                putComment: this.#commentsUpdateHandler,
                deleteComment: this.#commentsDeleteHandler
            });
        }


        /**
         * Comments error handler - create message modal popup.
         * @param {string} title - error title.
         * @param {string} message - error message.
         */
        #commentsError = (title, message) => {
            this.loader.stopLoader()
            this.msgModal.showMessageModal({
                title: title,
                text: message,
                color: 'warning',
                timeout: 2000,
            })
        }


        /**
         * Get comments handler - requesting comments from server.
         * @param {function} success - plugin function for handling GET success.
         * @param {function} error - plugin function for handling GET failure.
         */
        #commentsGetHandler = (success, error) => {
            var {currentPath, cakeId, basePath} = getCakePagePathVars()

            $.ajax({
                type: 'get',
                url: `${basePath}//comments?cakeId=${cakeId}`,
                success: function (comments) {
                    success(JSON.parse(comments))
                },
                error: () => this.#commentsError('Loading comments failed',
                    "Something went wrong while loading the cake comments")
            });
        }

        /**
         comment
         @typedef {Object} comment
         @property {number} CakeId .
         @property {number} UserId .
         @property {string} createdAt .
         @property {string} updatedAt .
         @property {string} content .
         */


        /**
         * Add comment handler - posting comment to the server.
         * @param {comment} commentJSON - comment object.
         * @param {function} success - plugin function for handling POST success.
         * @param {function} error - plugin function for handling POST failure.
         */
        #commentsPostHandler = (commentJSON, success, error) => {
            var that = this;
            this.loader.startLoader()
            var {currentPath, cakeId, basePath} = getCakePagePathVars()
            var newComment = {
                CakeId: cakeId,
                UserId: this.commentContainer.data("userid"),
                createdAt: commentJSON.createdAt.slice(0, 19).replace('T', ' '),
                updatedAt: commentJSON.updatedAt.slice(0, 19).replace('T', ' '),
                content: commentJSON.content,
            }
            $.ajax({
                type: 'post',
                url: `${basePath}//comments`,
                data: newComment,
                success: function (comment) {
                    success(JSON.parse(comment))
                    that.loader.stopLoader()
                },
                error: () => this.#commentsError('Posting comment failed',
                    "Something wen wrong while posting your comment")

            });
        }

        /**
         * Update comment handler - updating existing comment.
         * @param {comment} commentJSON - comment object.
         * @param {function} success - plugin function for handling PUT success.
         * @param {function} error - plugin function for handling PUT failure.
         */
        #commentsUpdateHandler = (commentJSON, success, error) => {
            var that = this;
            this.loader.startLoader()
            var {currentPath, cakeId, basePath} = getCakePagePathVars()
            var updatedComment = {
                updatedAt: new Date(commentJSON.updatedAt).toISOString().slice(0, 19).replace('T', ' '),
                content: commentJSON.content,
            }

            $.ajax({
                type: 'put',
                url: `${basePath}//comments?commentId=${commentJSON.id}`,
                data: updatedComment,
                success: function (comment) {
                    success(JSON.parse(comment))
                    that.loader.stopLoader()
                },
                error: () => this.#commentsError('Updating comment failed',
                    'Something went wrong while updating your comment')
            });
        }

        /**
         * Delete comment handler - deleting existing comment.
         * @param {comment} commentJSON - comment object.
         * @param {function} success - plugin function for handling DELETE success.
         * @param {function} error - plugin function for handling DELETE failure.
         */
        #commentsDeleteHandler = (commentJSON, success, error) => {
            var {currentPath, cakeId, basePath} = getCakePagePathVars()
            $.ajax({
                type: 'delete',
                url: `${basePath}//comments?commentId=${commentJSON.id}`,
                success: success,
                error: () => this.#commentsError('Deleting comment failed',
                    "Something went wrong while Deleting your comment")
            });
        }

    }

    /** Class cakeEdit Handle cake edit page functionality and edit requests. */
    class cakeEdit {

        /**
         * Initialize DOM elements references.
         * @param {objects} objects - object containing MessageModal instance.
         */
        constructor(objects) {
            this.msgModal = objects.msgModal
            this.loader = objects.loader
            this.elements = {
                cakeEditBtn: $('#edit-cake-btn'),
                minEditBtn: $('#btn1'),
                e_cakeEditBtnText: $('#edit-cake-btn p'),
                minEditBtnTxt: $('#btn1 b'),
                editFieldBtn: $('.edit-field-btn'),
                blinkingEditBtn: $('.fa-edit'),
                cancelEditBtn: $('.fas.fa-window-close'),
                deleteCakeBtn: $('#delete-cake'),
                submitBtn: $('.fa-check'),
                editMode: false,
                blinkingInterValRef: null,
                bakingTimeForm: $('#cake-bakingTime-input'),
                difficultyForm: $('#cake-difficulty-input'),
                servingsForm: $('#cake-servings-input'),
                titleForm: $('#cake-name-input'),
                descriptionForm: $('#cake-description-input'),
                ingredientsForm: $('#cake-ingredients-input'),
                recipeForm: $('#cake-recipe-input'),
                commentContainer: $('#comments-container'),
                messageModal: $('#message-modal'),
                overlayElement: $('#modal-overlay'),
            }
        }

        /**
         * Initialize page event listeners.
         */
        init() {
            var {cakeEditBtn, deleteCakeBtn, editFieldBtn, cancelEditBtn, minEditBtn} = this.elements

            cakeEditBtn.on('click', this.#handleEditClick);
            minEditBtn.on('click', this.#handleEditClick);
            deleteCakeBtn.on("click", this.#handleDeleteCakeClick);
            editFieldBtn.on("click", this.#fieldEditHandler)
            cancelEditBtn.on("click", this.#fieldCancelEditHandler)
            this.#initFormValidator()
        }

        /**
         * Custom required field validator - generating custom form validator.
         * @param {string} fieldName - form field name.
         */
        #requiredValidate(fieldName) {
            return {
                rules: {[fieldName]: {required: true}},
                submitHandler: (form) => this.#updateSubmitHandler(parseForm(form), fieldName)
            }
        }

        /**
         * Initialize form validation plugin.
         */
        #initFormValidator() {
            var {
                bakingTimeForm,
                difficultyForm,
                titleForm,
                descriptionForm,
                ingredientsForm,
                recipeForm,
                servingsForm
            } = this.elements
            $.validator.addMethod("pattern", function (value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                if (typeof param === "string") {
                    param = new RegExp("^(?:" + param + ")$");
                }
                return param.test(value);
            }, "Invalid format.");
            bakingTimeForm.validate(this.#requiredValidate("bakingTime"))
            difficultyForm.validate(this.#requiredValidate("difficulty"))
            titleForm.validate(this.#requiredValidate("name"))
            descriptionForm.validate(this.#requiredValidate("description"))
            ingredientsForm.validate(this.#requiredValidate("ingredients"))
            recipeForm.validate(this.#requiredValidate("recipe"))
            servingsForm.validate({
                rules: {
                    servings: {
                        required: true,
                        pattern: /(^[0-9]+[-]*[0-9]+$)/
                    }
                },
                messages: {
                    pattern: "Please match the following pattern - (number - number)"
                },
                submitHandler: (form) => this.#updateSubmitHandler(parseForm(form))
            })
        }


        /**
         * Edit cake click handler.
         * @param {object} e - click event.
         */
        #handleEditClick = (e) => {
            var that = this;
            var {e_cakeEditBtnText, minEditBtnTxt, cakeEditBtn, deleteCakeBtn, editFieldBtn} = this.elements
            var el = $(e.target)
            this.elements.editMode = !this.elements.editMode;
            if (this.elements.editMode) {
                this.#startBlinking()
                e_cakeEditBtnText.html("Done Edit")
                minEditBtnTxt.html("Done Edit")
                cakeEditBtn.addClass("action-selected")
            } else {
                this.#stopBlinking()
                e_cakeEditBtnText.html("Edit Cake")
                minEditBtnTxt.html("Edit Cake")
                cakeEditBtn.removeClass("action-selected")
            }
            // editMode ? startBlinking() : stopBlinking()
            deleteCakeBtn.css({display: this.elements.editMode ? "block" : "none"})
            editFieldBtn.each(function (index, editbtn) {
                $(editbtn).css({display: that.elements.editMode ? "block" : "none"})
            })
        }


        /**
         * Cancel edit field handler.
         */
        #fieldCancelEditHandler() {
            var el = $(this);
            var fieldInput = $(`#${el.data("for")}-input`)
            var fieldValue = $(`#${el.data("for")}-value`)
            var fieldSubmitBtn = $(`#${el.data("for")}-submit`)
            fieldSubmitBtn.removeClass("fa-check")
            fieldSubmitBtn.addClass("fa-edit")
            el.css({display: "none"})
            fieldInput.css({display: "none"})
            fieldValue.css({display: "block"})
        }

        /**
         * delete cake click handler.
         */
        #handleDeleteCakeClick = () => {
            this.msgModal.showMessageModal({
                title: 'Approve deleting',
                text: "Are you sure you want to delete this cake ?",
                okText: "Yes",
                cancelText: "No",
                onCancel: () => {
                },
                color: 'warning',
                onOk: this.#deleteCake,
            })
        }

        /**
         * delete cake - sending DELETE request to the server.
         */
        #deleteCake = () => {
            this.msgModal.closeMessageModal()
            this.loader.startLoader()
            var that = this;
            var {cakeId, basePath} = getCakePagePathVars()
            var request = $.ajax({
                type: "delete",
                url: `${basePath}//delete?cakeId=${cakeId}`,
            })
            request.done(function () {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Cake deleted',
                    text: "Cake deleted successfully,</br> you'll be redirected to your cakes page",
                    color: 'success',
                    timeout: 2500,
                    onTimeout: () => {
                        window.location.href = basePath + "/MyCakes"
                    },
                })
            });
            request.fail(function (jqXHR) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Server Error',
                    text: "Something went wrong",
                    color: 'alert',
                    timeout: 2000,
                })
            });

        }


        /**
         * delete cake - sending DELETE request to the server.
         */
        #fieldEditHandler() {
            var el = $(this);
            var fieldInput = $(`#${el.data("for")}-input`)
            var fieldValue = $(`#${el.data("for")}-value`)
            var fieldCancelBtn = $(`#${el.data("for")}-cancel`)

            if (fieldInput.css('display') === 'none') {
                el.removeClass("fa-edit")
                el.addClass("fa-check")

                el.css({color: "#b8db57"})

                fieldCancelBtn.css({display: "block"})
                fieldInput.css({display: "block"})
                fieldValue.css({display: "none"})
            } else {
                if (fieldInput.valid()) {
                    fieldInput.submit()

                    el.removeClass("fa-check")
                    el.addClass("fa-edit")
                    fieldCancelBtn.css({display: "none"})
                    fieldInput.css({display: "none"})
                    fieldValue.css({display: "block"})
                }
            }
        }

        /**
         * edit icon blinking functionality.
         */
        #blink() {
            var {blinkingEditBtn} = this.elements
            blinkingEditBtn.each(function (index, editbtn) {
                if ($(editbtn).hasClass("fa-edit"))
                    $(editbtn).css({color: "#fd9999"})
            })
            setTimeout(function () {
                blinkingEditBtn.each(function (index, editbtn) {
                    if ($(editbtn).hasClass("fa-edit"))
                        $(editbtn).css({color: "#8aabf5"})
                })
            }, 500);
        }

        /**
         * start edit icon blinking.
         */
        #startBlinking = () => {
            var self = this;
            var {blinkingInterValRef} = this.elements
            blinkingInterValRef = setInterval(function () {
                self.#blink();
            }, 1000);
        }

        /**
         * stop edit icon blinking.
         */
        #stopBlinking() {
            var {blinkingInterValRef} = this.elements
            clearInterval(blinkingInterValRef);
        }

        /**
         * Update cake submit handler - send post request for updating cake details.
         * @param {array} data- array of cake recipe lines.
         * @param {object} fieldName - form field name (ingredients or recipe).
         */
        #updateSubmitHandler = (data, fieldName) => {
            this.loader.startLoader()
            var self = this;
            var parsedList;
            if (fieldName === "recipe" || fieldName === "ingredients") {
                parsedList = this.#parseListTextarea(data[fieldName])
                data[fieldName] = parsedList.join("|")
            }
            var fieldValue = $(`#cake-${fieldName}-value`)
            var {currentPath, cakeId, basePath} = getCakePagePathVars()
            var request = $.ajax({
                type: "post",
                data: data,
                cache: false,
                url: `${basePath}//updateCake?cakeId=${cakeId}`,
            })
            request.done(function () {
                self.loader.stopLoader()
                self.msgModal.showMessageModal({
                    title: 'Update success',
                    text: "You successfully updated the cake",
                    color: 'success',
                    timeout: 2000,
                    onTimeout: () => self.#onUpdateSucceed(fieldValue, data, fieldName, parsedList),
                })
            });
            request.fail(function (jqXHR) {
                self.loader.stopLoader()
                self.msgModal.showMessageModal({
                    title: 'Server Error',
                    text: "something went wrong",
                    color: 'alert',
                    timeout: 2000,
                })
            });
        }

        /**
         * Update cake succeed handler.
         * @param {object} fieldValue- html element representing the target text node.
         * @param {array} data- array of cake recipe lines.
         * @param {object} fieldName - form field name (ingredients or recipe).
         * @param {object} parsedList - processed text list data.
         */
        #onUpdateSucceed(fieldValue, data, fieldName, parsedList) {
            if (fieldName === "recipe" || fieldName === "ingredients") {
                this.#lineListToHtmlList($(`#cake-${fieldName}-value`), parsedList)
            } else if (fieldName === "difficulty") {
                fieldValue.html(data[fieldName] === "1" ? "Easy" : data[fieldName] === "2" ? "Medium" : "Hard")
            } else {
                fieldValue.html(data[fieldName])
            }
        }

        /**
         * Convert page list format to db list format.
         * @param {string} text- raw text from user input.
         */
        #parseListTextarea(text) {
            var linesArr = text.split(/\n/).map(function (line) {
                var parsedLine = line.substr(line.indexOf(")") + 1, line.indexOf("\r") - 2).trim()
                if (parsedLine.length !== 0) {
                    return parsedLine
                }
            })
            return linesArr;
        }

        /**
         * convert db list format to page list format.
         * @param {object} ul- html ul element.
         * @param {array} lines- array of list lines.
         */
        #lineListToHtmlList(ul, lines) {
            var liList = $.map(lines, function (value) {
                if (value)
                    return '<li>' + value + '</li>'
            }).join('');
            $(ul).html(liList);
        }

    }


    /**
     cake
     @typedef {Object} cake
     @property {string} name .
     @property {string} description .
     @property {string} ingredients .
     @property {string} recipe .
     @property {string} image .
     @property {string} image3d .
     @property {number} bakingTime .
     @property {number} difficulty .
     @property {string} servings .
     @property {string} otherRestriction .
     @property {array} [tags] .
     @property {array} [restrictions] .
     */

    /**
     cakeData
     @typedef {Object} cakeData
     @property {cake} cake .
     @property {array} tags .
     */


    /** Class CakeMaker Handle cake creation - handle user inputs, external api calls, post final cake to the server. */
    class CakeMaker {

        /**
         * Initialize DOM elements references, and declaring default cake data.
         * @param {objects} objects - object containing MessageModal instance.
         */
        constructor(objects) {
            this.msgModal = objects.msgModal
            this.loader = objects.loader
            this.elements = {
                currentQuestionsPage: 1,
                currentSteps: ['@Setp 1', 'Step 2 ', 'Step 3 '],
                steps: $('#steps'),
                backBtn: $("#back-btn"),
                nextBtn: $("#next-btn"),
                imageBtn: $("#questions3 img"),
                cakeMakerForm: $("#cake-maker-form"),
                dessertImages: $(".img-selection"),
                messageModal: $('#message-modal'),
                overlayElement: $('#modal-overlay'),
                loaderElement: $("#loader")
            }
            this.defaultCakeData = {
                cake: {
                    name: "Chocolate Cake",
                    description: "Chocolate Cake with berries",
                    ingredients: "chocolate|vanilla|eggs|milk|sugar|",
                    recipe: "Melt the chocolate|whisk the eggs on medium speed|Add vanilla|bake in oven|",
                    image: "public/images/cake_default.jpg",
                    image3d: "public/images/cake3d_default.png",
                    bakingTime: 50,
                    difficulty: 3,
                    servings: "6-8"
                },
                tags: []
            }
        }

        /**
         * Initialize page event listeners.
         */
        init() {
            var {nextBtn, backBtn, imageBtn} = this.elements
            this. #initProgressBarPlugin()
            this. #initFormValidation()
            nextBtn.on("click", this.#nextFormPageHandler)
            backBtn.on("click", this.#backFormPageHandler)
            imageBtn.on("click", this.#imageSelectionHandler)
            backBtn.text("")
        }

        /**
         * Initialize progress bar plugin.
         */
        #initProgressBarPlugin() {
            var {steps} = this.elements
            steps.progressbar({
                steps: ['@Setp 1', 'Step 2 ', 'Step 3 ']
            });
        }

        /**
         * Initialize form validation plugin.
         */
        #initFormValidation() {
            var that = this
            var {cakeMakerForm} = this.elements
            cakeMakerForm.validate({
                submitHandler: (form) => that.#cakeMakerFormSubmitHandler(parseComplexForm(form))
            });
        }

        /**
         * Handle next form page btn click.
         */
        #nextFormPageHandler = () => {
            var {nextBtn, backBtn, cakeMakerForm, currentQuestionsPage, currentSteps} = this.elements
            if (nextBtn.hasClass("finish")) {
                cakeMakerForm.submit()
            }
            if (this.elements.currentQuestionsPage < 3) {
                backBtn.html('<i class="fas fa-arrow-left"></i>Back')
                this.#resetSteps()
                this.elements.currentQuestionsPage++
                this.elements.currentSteps[this.elements.currentQuestionsPage - 1] = '@' + this.elements.currentSteps[this.elements.currentQuestionsPage - 1]
                $('#steps').progressbar({
                    steps: this.elements.currentSteps
                });
                $(`#questions` + this.elements.currentQuestionsPage).removeClass("hide_section")
            }
            if (this.elements.currentQuestionsPage === 3) {
                nextBtn.html('Finish<i class="fas fa-arrow-right"></i>')
                nextBtn.addClass("finish")
            } else {
                nextBtn.html('Next<i class="fas fa-arrow-right"></i>')
            }
        }

        /**
         * Handle back form page btn click.
         */
        #backFormPageHandler = () => {
            var {nextBtn, backBtn, currentQuestionsPage, currentSteps} = this.elements
            nextBtn.removeClass("finish")
            if (this.elements.currentQuestionsPage > 1) {
                this.#resetSteps()
                this.elements.currentQuestionsPage--
                this.elements.currentSteps[this.elements.currentQuestionsPage - 1] = '@' + this.elements.currentSteps[this.elements.currentQuestionsPage - 1]
                $('#steps').progressbar({
                    steps: this.elements.currentSteps
                });

                $(`#questions` + this.elements.currentQuestionsPage).removeClass("hide_section")
                if (this.elements.currentQuestionsPage === 1) {
                    $(this).css("color", '#f1c7c7')
                }
            }
            if (this.elements.currentQuestionsPage < 3) {
                nextBtn.html('Next<i class="fas fa-arrow-right"></i>')
            }
            if (this.elements.currentQuestionsPage === 1) {
                backBtn.html('')
            }
        }

        /**
         * Reset form progress bar steps.
         */
        #resetSteps() {
            $(`.questions`).each((i, el) => {
                $(el).addClass("hide_section")
            })
            this.elements.currentSteps = ['Setp 1', 'Step 2 ', 'Step 3 ']
            $('#steps').remove()
            $('#steps-container').append($("<div id='steps'></div>"))
        }

        /**
         * Handle image selection.
         */
        #imageSelectionHandler() {
            if ($(this).hasClass("selected")) {
                $(this).removeClass("selected")
            } else {
                $(this).addClass("selected")
            }
        }

        /**
         * Validate minimum image selection requirement.
         * @param {number} minVal - minimum selection limit.
         */
        #validateImagesSelection(minVal) {
            var {dessertImages} = this.elements
            var selections = [];
            dessertImages.each(function () {
                if ($(this).hasClass("selected")) {
                    selections.push($(this).attr('id'))
                }
            })
            if (selections.length < minVal) {
                return false
            }
            return selections;
        }

        /**
         * Cake maker form submit handler.
         * @param {cake} data - cake data generated from user inputs.
         */
        #cakeMakerFormSubmitHandler = (data) => {
            this.loader.startLoader();
            if (!data.restrictions) {
                data.restrictions = []
            }
            if (!Array.isArray(data.restrictions)) {
                data.restrictions = [data.restrictions]
            }
            if (data.otherRestriction && data.otherRestriction !== "") {
                data.restrictions.push(data.otherRestriction)
            }
            data.tags = [data.tags]

            var selectedImages = this.#validateImagesSelection(3)
            if (selectedImages) {
                data.tags = [...data.tags, ...selectedImages]
            } else {
                this.msgModal.showMessageModal({
                    title: 'Selection required',
                    text: "Please select at least 3 Favorite deserts",
                    color: 'alert',
                    timeout: 2000,
                })
                this.loader.stopLoader()
                return
            }
            this.#generateCake(data)
        }

        /**
         * Generate new cake from user input.
         * @param {cake} data - cake data generated from user inputs.
         */
        #generateCake(data) {
            var cakeData = {
                cake: {
                    // UserId:"",
                    name: "",
                    description: "",
                    ingredients: "",
                    recipe: "",
                    image: "",
                    image3d: "",
                    bakingTime: data.bakingTime,
                    difficulty: data.difficulty,
                    servings: getRandomNumber(4, 7) + "-" + getRandomNumber(8, 13)
                },
                tags: [...data.tags, ...data.restrictions]
            }


            this.#getCakeFromApi(cakeData, this.#onGetRecipeSuccess, this.#onGetRecipeFailed)

        }

        /**
         * Handle final step of cake building after gathering data from external api's.
         * @param {cakeData} cakeData - final cake data object.
         */
        #onDoneCallsToApis(cakeData) {
            this.#saveCakeOnServer(cakeData)
        }

        /**
         * Handle sending post request to the server to save the new cake.
         * @param {cakeData} cakeData - final cake data object.
         */
        #saveCakeOnServer(cakeData) {
            var that = this
            var currentPath = window.location.href
            var basePath = currentPath.substr(0, currentPath.lastIndexOf("/"))
            var userId = $('#cake-maker-form').data("userid");
            $.ajax({
                type: 'post',
                url: `${basePath}/addCake?userId=${userId}`,
                data: cakeData,
                success: function (data) {
                    that.loader.stopLoader()
                    that.msgModal.showMessageModal({
                        title: 'Cake is ready',
                        text: "You will be redirected to cake page",
                        color: 'success',
                        onTimeout: () => {
                            window.location.href = `${basePath}?cakeId=${data}`
                        },
                        timeout: 2000,
                    })
                },
                error: () => {
                    that.loader.stopLoader()
                    that.msgModal.showMessageModal({
                        title: 'Server Error',
                        text: "something went wrong while saving your cake",
                        color: 'alert',
                        timeout: 2000,
                    })
                }

            });
        }


        /**
         * Handle successful call to external api for cake recipe query.
         * @param {cakeData} cakeData - final cake data object.
         */
        #onGetRecipeSuccess = (cakeData) => {
            this.#get3dImageFromApi(cakeData, this.#onGet3dImageSuccess, this.#onGetGet3dImageFailed)
        }

        /**
         * Handle successful call to external api for cake 3d image.
         * @param {cakeData} cakeData - final cake data object.
         */
        #onGet3dImageSuccess = (cakeData) => {
            this.#onDoneCallsToApis(cakeData)
        }

        /**
         * Handle fault call to external api for cake recipe query - will replace with default data.
         * @param {cakeData} cakeData - final cake data object.
         * @param {string} msg - message about the specific failed step.
         */
        #onGetRecipeFailed = (msg, cakeData) => {
            cakeData.cake.name = this.defaultCakeData.cake.name
            cakeData.cake.description = this.defaultCakeData.cake.description
            cakeData.cake.ingredients = this.defaultCakeData.cake.ingredients
            cakeData.cake.recipe = this.defaultCakeData.cake.recipe
            cakeData.cake.image = this.defaultCakeData.cake.image
            this.#get3dImageFromApi(cakeData, this.#onGet3dImageSuccess, this.#onGetGet3dImageFailed)
        }

        /**
         * Handle fault call to external api for cake 3d image - will replace with default image.
         * @param {cakeData} cakeData - final cake data object.
         * @param {string} msg - message about the specific failed step.
         */
        #onGetGet3dImageFailed = (msg, cakeData) => {
            cakeData.cake.image3d = this.defaultCakeData.cake.image3d
            this.#onDoneCallsToApis(cakeData)
        }

        /**
         * Request cake recipes from external api.
         * @param {cakeData} cakeData - final cake data object.
         * @param {function} success - success handler function.
         * @param {function} fail - fail handler function.
         */
        #getCakeFromApi(cakeData, success, fail) {
            var request = $.ajax({
                cache: false,
                url: `https://api.spoonacular.com/recipes/complexSearch?number=50&instructionsRequired=true&type=dessert&titleMatch=cake&addRecipeInformation=true&apiKey=ac55544b62f44ed389773b05af2e978e`,
            })
            request.done(function (data) {
                if (data && data.results.length !== 0) {
                    var chosenCake = data.results[getRandomNumber(0, data.results.length - 1)]
                    if (chosenCake.title && chosenCake.image && chosenCake["summary"]) {
                        cakeData.cake.name = chosenCake.title
                        cakeData.cake.image = chosenCake.image
                        cakeData.cake.description = chosenCake["summary"]
                        if (chosenCake.analyzedInstructions && chosenCake.analyzedInstructions[0] &&
                            chosenCake.analyzedInstructions[0].steps && chosenCake.analyzedInstructions[0].steps.length !== 0) {
                            var analyzedInstructions = chosenCake.analyzedInstructions[0].steps
                            analyzedInstructions.forEach(instruction => {
                                cakeData.cake.recipe += instruction.step + "|"
                                instruction.ingredients.forEach(ingredient => {
                                    cakeData.cake.ingredients += ingredient.name + "|"
                                })
                            })
                        } else {
                            fail("cake instructions failed", cakeData)
                        }
                    } else {
                        fail("cake title or image path failed", cakeData)
                    }
                } else {
                    fail("cake resuts data failed", cakeData)
                }
                success(cakeData)
            });
            request.fail(function (jqXHR) {
                fail("get recipe request failed", cakeData)
            });
        }

        /**
         * Request cake 3d image from external api.
         * @param {cakeData} cakeData - final cake data object.
         * @param {function} success - success handler function.
         * @param {function} fail - fail handler function.
         */
        #get3dImageFromApi(cakeData, success, fail) {
            var imagePath = ""
            var request = $.ajax({
                crossDomain: true,
                url: "https://bing-image-search1.p.rapidapi.com/images/search?q=3d%20cake",
                method: "GET",
                headers: {
                    "x-rapidapi-key": "995c12ca1bmsh927c8da42358ce0p1619abjsn59e5d4ba971a",
                    "x-rapidapi-host": "bing-image-search1.p.rapidapi.com"
                }
            })


            request.done(function (data) {
                if (data && data.value && data.value.length !== 0) {
                    imagePath = data.value[getRandomNumber(0, data.value.length - 1)]
                    if (imagePath && imagePath.contentUrl && imagePath.contentUrl.length !== 0) {
                        cakeData.cake.image3d = imagePath.contentUrl
                    } else {
                        fail("image or image url length failed", cakeData)
                    }
                } else {
                    fail("image data or data length failed", cakeData)
                }

                success(cakeData)

            });
            request.fail(function (jqXHR) {
                fail("image request failed", cakeData)

            });
        }


    }

    /** Class Auth Handle user authentication - signup and login functionality. */
    class Auth {

        /**
         * Initialize DOM elements references.
         * @param {objects} objects - object containing MessageModal instance.
         */
        constructor(objects) {
            this.msgModal = objects.msgModal
            this.loader = objects.loader
            this.elements = {
                switchBtn: $('#switch-login-btn'),
                loginForm: $('#login-form'),
                signupForm: $('#signup-form')
            }
        }

        /**
         * Initialize page event listeners, and form validations.
         */
        init() {
            this.#initFormValidation()
            this.#loginValidate()
            this.#signupValidate()
            this.elements.switchBtn.on("click", this.#toggleSignupLogin)
        }

        /**
         * Create custom form validators.
         */
        #initFormValidation() {

            $.validator.addMethod("checkPassword", function (value, element, param) {
                return this.optional(element) || (value === $(param).val());
            }, "passwords dont match");

            $.validator.addMethod("pattern", function (value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                if (typeof param === "string") {
                    param = new RegExp("^(?:" + param + ")$");
                }
                return param.test(value);
            }, "Invalid format.");
        }

        /**
         * Login form validation settings.
         */
        #loginValidate() {
            var that = this
            this.elements.loginForm.validate({
                rules: {
                    username: {
                        pattern: /^\w+$/i,
                        required: true,
                    },
                    password: {
                        pattern: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/,
                        required: true,
                    }
                },
                messages: {
                    username: {
                        pattern: "Letters, numbers, and underscores only please",
                        required: "Please provide a username",
                    },
                    password: {
                        pattern: "7 to 15 characters which contain at least one numeric digit and a special character",
                        required: "Please provide a password",
                    },
                },

                submitHandler: (form) => {
                    that.#loginHandler(parseForm(form))
                }
            });
        }

        /**
         * Signup form validation settings.
         */
        #signupValidate() {
            var that = this
            this.elements.signupForm.validate({
                rules: {
                    fullname: {
                        pattern: /^(?!.{52,})[a-zA-Z.'-]{2,50}(?: [a-zA-Z.'-]{2,50})+$/,
                        required: true,
                    },
                    nickname: {
                        pattern: /^[a-zA-Z\s]*$/,
                        required: true,
                        remote: `${window.location.href}`
                    },
                    username: {
                        pattern: /^\w+$/i,
                        required: true,
                        remote: `${window.location.href}`
                    },
                    password: {
                        pattern: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/,
                        required: true,
                    },
                    confirmpassword: {
                        checkPassword: "#spassword",
                        required: true,
                    }
                },
                messages: {
                    fullname: {
                        pattern: "Required at least two words with one space and at least two letters for each word",
                        required: "Please provide a full name",
                    },
                    nickname: {
                        pattern: "Letters and spaces only please",
                        required: "Please provide a nickname",
                        remote: "That nickname is already taken"
                    },
                    username: {
                        pattern: "Letters, numbers, and underscores only please",
                        required: "Please provide a username",
                        remote: "That username is already taken"
                    },
                    password: {
                        pattern: "7 to 15 characters which contain at least one numeric digit and a special character",
                        required: "Please provide a password",
                    },
                    confirmpassword: {
                        checkPassword: "passwords dont match",
                        required: "Please provide a password confirmation",
                    },
                },

                submitHandler: (form) => {
                    var tags = [];
                    $("input:checkbox:checked").each(function () {
                        tags.push($(this).val());
                    });
                    var data = {...parseForm(form), tags}
                    // signupHandler(JSON.stringify(data))
                    that.#signupHandler(data)
                }
            });
        }

        /**
         * Toggle login/signup form handler.
         */
        #toggleSignupLogin = () => {
            var {loginForm, signupForm, switchBtn} = this.elements
            if (loginForm.css('display') === 'flex') {
                loginForm.css({display: "none"});
                signupForm.css({display: "flex"});
                switchBtn.text("Switch to Login");
            } else {
                loginForm.css({display: "flex"});
                signupForm.css({display: "none"});
                switchBtn.text("Switch to Signup");
            }
        }

        /**
         authData
         @typedef {Object} authData
         @property {string} username
         @property {string} password
         @property {string} [fullname]
         @property {string} [nickname]
         @property {string} [username]
         @property {string} [confirmpassword]
         */

        /**
         * Handle login form submission.
         * @param {authData} data - login form data.
         */
        #loginHandler = (data) => {
            var that = this
            this.loader.startLoader()
            var request = $.ajax({
                type: "post",
                data: data,
                cache: false,
                url: `${window.location.href}/login`,
            })
            request.done(function (data) {
                that.loader.stopLoader()
                var homePath = window.location.href.split('/');
                homePath.pop();
                that.msgModal.showMessageModal({
                    title: 'Login Success',
                    text: "you'll be redirected to homepage",
                    color: 'success',
                    timeout: 1500,
                    onTimeout: () => {
                        window.location.href = homePath.join('/')
                    },
                    showExitBtn: false,
                    onExit: () => {
                        $("#username").val('')
                    }
                })
            });
            request.fail(function (jqXHR) {
                that.loader.stopLoader()
                if (jqXHR.status === 404) {
                    that.msgModal.showMessageModal({
                        title: 'Login Error',
                        text: "Username not found",
                        color: 'warning',
                        timeout: 2000,
                        onExit: () => {
                            $("#username").val('')
                        }
                    })
                } else if (jqXHR.status === 401)
                    that.msgModal.showMessageModal({
                        title: 'Login Error',
                        text: "Invalid password",
                        color: 'alert',
                        timeout: 2000,
                        onExit: () => {
                            $("#password").val('')
                        }
                    })
            });
        }
        /**
         * Handle signup form submission.
         * @param {authData} data - signup form data.
         */
        #signupHandler = (data) => {
            var that = this
            this.loader.startLoader()
            var request = $.ajax({
                type: "post",
                dataType: "json",
                data: data,
                cache: false,
                url: `${window.location.href}/signup`,
            })
            request.done(function (data) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Signup Success',
                    text: "you'll be redirected to login",
                    color: 'success',
                    okText: "Go to Login",
                    onOk: that.#toggleSignupLogin,
                    timeout: 2000,
                    onTimeout: that.#toggleSignupLogin,
                    showExitBtn: false,
                    onExit: () => {
                        $("#username").val('')
                    }
                })
            });
            request.fail(function (jqXHR) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Server Error',
                    text: "something went wrong",
                    color: 'alert',
                    timeout: 2000,
                })
            });
        }

    }

    /** Class UpdateProfileModal Handle update profile process - handle modal functionality and srver calls for updating user profile. */
    class UpdateProfileModal {

        /**
         * Initialize DOM elements references, and declaring default cake data.
         * @param {objects} objects - object containing MessageModal instance.
         */
        constructor(objects) {
            this.msgModal = objects.msgModal
            this.loader = objects.loader
            this.elements = {
                personalInfoForm: $('#personal-info-form'),
                changePasswordForm: $('#change-password-form'),
                preferencesForm: $('#preferences-form'),
                deleteAccountBtn: $('#delete-account-btn'),
                overlayElement: $('#modal-overlay'),
                openBtn: $("#update-profile-btn"),
                modalElement: $('#update-profile-modal'),
                closeBtn: $('#update-profile-modal').find('#close-modal-btn'),
                accordion: $('#update-profile-modal').find('.accordion'),
            }
        }

        /**
         * Initialize page event listeners and form validation plugin.
         */
        init() {

            this.#attachClickHandlers()
            this.#initAccordion()
            this.#initFormValidation()
            this.#preferenceFormValidation()
            this.#changePasswordFormValidation()
            this.#personalInfoFormValidation()
            this.elements.deleteAccountBtn.on('click', this.#handleDeleteAccountClick)
        }

        /**
         * Attach click listeners for opening and closing the update profile modal.
         */
        #attachClickHandlers() {
            var {openBtn, closeBtn, modalElement, overlayElement} = this.elements
            openBtn.on('click', function () {
                modalElement.css({display: "block"})
                overlayElement.css({display: "block"})
            })

            closeBtn.on('click', function () {
                modalElement.css({display: "none"})
                overlayElement.css({display: "none"})
            })
        }

        /**
         * handle accordion structure functionality.
         */
        #initAccordion() {
            this.elements.accordion.each(function () {
                var el = $(this)
                var icon = $($(this).find("i")[0])

                el.on('click', function () {
                    $(el).toggleClass("active")

                    if (icon.hasClass("fa-plus")) {
                        icon.removeClass("fa-plus")
                        icon.addClass("fa-minus")
                    } else {
                        icon.removeClass("fa-minus")
                        icon.addClass("fa-plus")
                    }
                    if ($(el).next().css('display') == 'block') {
                        $(el).next().css({display: "none"});
                    } else {
                        $(el).next().css({display: "block"});
                    }
                })
            })
        }

        /**
         * creat custom form validators.
         */
        #initFormValidation() {
            //Defining new methods for validator
            $.validator.addMethod("checkPassword", function (value, element, param) {
                return this.optional(element) || (value === $(param).val());
            }, "passwords dont match");

            $.validator.addMethod("pattern", function (value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                if (typeof param === "string") {
                    param = new RegExp("^(?:" + param + ")$");
                }
                return param.test(value);
            }, "Invalid format.");
        }

        /**
         * Attach preferences form validation settings.
         */
        #personalInfoFormValidation =()=> {
            //personal info form
            this.elements.personalInfoForm.validate({
                rules: {
                    fullname: {
                        pattern: /^(?!.{52,})[a-zA-Z.'-]{2,50}(?: [a-zA-Z.'-]{2,50})+$/,
                        required: true,
                    },
                    nickname: {
                        pattern: /^[a-zA-Z\s]*$/,
                        required: true,
                        remote: `${window.basePath}/auth`
                    },
                },
                messages: {
                    fullname: {
                        pattern: "Required at least two words with one space and at least two letters for each word",
                        required: "Please provide a full name",
                    },
                    nickname: {
                        pattern: "Letters and spaces only please",
                        required: "Please provide a nickname",
                        remote: "That nickname is already taken"
                    },
                },

                submitHandler: (form) => this.#personalInfoHandler(parseForm(form))
            });
        }

        /**
         * Attach change password form validation settings.
         */
        #preferenceFormValidation  = () => {
            var that = this;
            this.elements.preferencesForm.validate({
                submitHandler: (form) => {
                    var tags = [];
                    $("input:checkbox:checked").each(function () {
                        tags.push($(this).val());
                    });
                    that.#personalInfoHandler({tags})
                }
            })
        }

        /**
         * Attach personal info form validation settings.
         */
        #changePasswordFormValidation= () => {
            //change password info form
            this.elements.changePasswordForm.validate({
                rules: {

                    password: {
                        pattern: /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/,
                        required: true,
                    },
                    confirmPassword: {
                        checkPassword: "#password",
                        required: true,
                    }
                },
                messages: {

                    password: {
                        pattern: "7 to 15 characters which contain at least one numeric digit and a special character",
                        required: "Please provide a password",
                    },
                    confirmPassword: {
                        checkPassword: "passwords dont match",
                        required: "Please provide a password confirmation",
                    },
                },

                submitHandler: (form) => this.#personalInfoHandler(parseForm(form))
            });
        }


        /**
         updateInfoData
         @typedef {Object} updateInfoData
         @property {string} [fullname]
         @property {string} [nickname]
         @property {string} [password]
         @property {string} [confirmPassword]
         */


        /**
         * personal info form submit handler.
         * @param {updateInfoData} data - personal info form data.
         */
        #personalInfoHandler = (data) => {
            var that = this
            this.loader.startLoader()
            var request = $.ajax({
                type: "post",
                data: data,
                cache: false,
                url: `${window.basePath}/auth/updateinfo`,
            })
            request.done(function (data) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Update succeed',
                    text: "your personal info has been updated",
                    color: 'success',
                    timeout: 2000,
                })
            });
            request.fail(function (jqXHR) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Server Error',
                    text: "something went wrong",
                    color: 'alert',
                    timeout: 2000,
                })
            });
        }

        /**
         * delete account click handler - will show message modal for confirmation.
         * @param {object} e - click event.
         */
        #handleDeleteAccountClick = (e) => {
            var that = this
            var userid = $(e.target).data("userid")
            this.msgModal.showMessageModal({
                title: 'Delete account',
                text: "Are you sure you want to delete your account ?",
                color: 'warning',
                onOk: () => that.#deleteAccount(userid),
                onCancel: that.msgModal.closeMessageModal,
                okText: 'Yes',
                cancelText: 'No'
            })
        }


        /**
         * delete account handler - will send DELETE request to the server.
         * @param {Number} userid - personal info form data.
         */
        #deleteAccount = (userid) => {
            var that = this
            this.loader.startLoader()
            var request = $.ajax({
                type: "delete",
                url: `${window.basePath}/auth?userid=${userid}`,
            })
            request.done(function (data) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'delete account succeed',
                    text: "your account has been deleted",
                    color: 'success',
                    timeout: 2000,
                    onTimeout: () => {
                        window.location.href = window.basePath
                    }
                })
            });
            request.fail(function (jqXHR) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Server Error',
                    text: "something went wrong",
                    color: 'alert',
                    timeout: 2000,
                })
            });
        }

    }


    /**
     * initialize the site search bar validation.
     */
    function initSearchForm() {
        var searchForm = $('#search')
        var searchFormBtn = $('#search i')
        searchForm.validate({
            rules: {
                search: {
                    required: true
                }
            },
            messages: {
                search: {
                    required: ""
                }
            }
        })
        searchFormBtn.on('click', () => search(searchForm))
    }


    /**
     * validating search form input.
     * @param {object} searchForm - jquery form element object.
     */
    function search(searchForm) {
        if (searchForm.validate()) {
            searchForm.submit();
        }
    }

    /**
     * parse cake page url and extracting cakeId.
     */
    function getCakePagePathVars() {
        var currentPath = window.location.href
        var cakeId = currentPath.substr(currentPath.lastIndexOf("=") + 1);
        var basePath = currentPath.substr(0, currentPath.lastIndexOf("/"))
        return {currentPath, cakeId, basePath}
    }


    /**
     * Generating user avatar based on the user full name.
     */
    function initAvatar() {
        var avatarElement = $('.avatar-initials')
        if (avatarElement.length !== 0) {
            var colors = ["#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085",
                    "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", "#f1c40f", "#e67e22",
                    "#e74c3c", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d"],
                name = avatarElement.data('name'),
                initials = name.split(' ')[0].charAt(0).toUpperCase() + name.split(" ")[1].charAt(0).toUpperCase(),
                charIndex = initials.charCodeAt(0) - 65,
                colorIndex = charIndex % 19;

            avatarElement.css({
                'background-color': colors[colorIndex],
            })
                .html(initials);
        }

    }


    /**
     * Convert form data to key value pairs.
     * @param {object} form - jquery form element object.
     */
    function parseForm(form) {
        return $(form).serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
    }

    /**
     * Convert form data to key value pairs for custom form with checkboxes.
     * @param {object} form - jquery form element object.
     */
    function parseComplexForm(form) {
        return $(form).serializeArray().reduce(function (obj, item) {
            if (obj[item.name] && !Array.isArray(obj[item.name])) {
                obj[item.name] = [obj[item.name]]
            }
            if (Array.isArray(obj[item.name])) {
                obj[item.name].push(item.value)
            } else {
                obj[item.name] = item.value;
            }
            return obj;
        }, {});
    }


    /**
     * Initiaize card flip plugin.
     */
    function initCardsFlipperAddon() {
        var cards = $(".obj-card"),
            flipBtns = $(".flip")
        cards.each((i, el) => {
            $(el).flip({axis: 'x', trigger: 'manual'});
        })
        flipBtns.on("click", cardFlipHandler)
        $('.back').css('display', 'block')
    }

    /**
     * Flipping cards handler.
     * @param {object} e - click event.
     */
    function cardFlipHandler(e) {
        var currentElement = $(e.target).parent().parent().siblings()[0]
        $($(currentElement).children()[0]).flip('toggle')
    }


    /** Class Rating Handle the star rating plugin - handle server requests for posting cake ratings. */
    class Rating {


        /**
         * Initialize DOM elements references.
         * @param {objects} objects - object containing MessageModal instance.
         */
        constructor(objects) {
            this.msgModal = objects.msgModal
            this.loader = objects.loader
            this.elements = {
                stars: $(".stars")
            }
        }

        /**
         * Initialize page event listeners.
         */
        init() {
            var that = this;
            this.elements.stars.each((i, el) => {
                $(el).starRating({
                    initialRating: $(el).data("rating"),
                    strokeColor: '#894A00',
                    strokeWidth: 10,
                    starSize: 15,
                    callback: that.#onRating
                })
            })
        }


        /**
         * Handle post/update cake rating.
         * @param {number} currentRating - the current cake rating.
         * @param {object} el - html element containing the user id as data attribute.
         */
        #onRating = (currentRating, el) => {
            var that = this;
            this.loader.startLoader()
            var request = $.ajax({
                // dataType:"json",
                data: {cakeId: $(el).data("id"), rating: currentRating},

                url: $(el).data("path") + "/rate",
            })
            request.done(function (data) {
                that.loader.stopLoader()
                that.msgModal.showMessageModal({
                    title: 'Rating succeed',
                    text: "you successfully rated this cake",
                    color: 'success',
                    timeout: 2000,
                })
            });
            request.fail(function (jqXHR) {
                that.loader.stopLoader()
                if (jqXHR.status === 404) {
                    that.msgModal.showMessageModal({
                        title: 'Login Required',
                        text: "Signup or Login to rate cakes",
                        color: 'warning',
                        okText: 'Signup/Login',
                        cancelText: "Later",
                        onOk: () => {
                            window.location.href = $(el).data("path") + "/auth"
                        },
                    })
                } else {
                    that.msgModal.showMessageModal({
                        title: 'Server Error',
                        text: "Something went wrong",
                        color: 'alert',
                        timeout: 2000,
                    })
                }
            });
        }

    }


    /**
     * Random number generator.
     * @param {number} min - minimum number limit.
     * @param {number} max - maximum number limit.
     */
    function getRandomNumber(min, max) {
        min = Math.ceil(min);
        max = Math.floor(max);
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    /**
     * Handle window resize - adjust element by window size.
     */
    function windowResizeHandler() {
        var actionDrawer = $('.min-action-btns')
        var actionDrawerBtn = $(".min-action-nav-btn")
        var sideBar = $("nav ul");
        var hamburger = $("#hamburger")
        var win = $(this);
        if (win.width() >= 810) {
            hamburger.removeClass('open');
            sideBar.removeClass("opened")
        }
        if (win.width() > 650) {
            if (actionDrawerBtn.find("i").hasClass("fa-minus")) {
                actionDrawer.css("height", "45px")
                $(".min-action-btns #btn2,.min-action-btns #btn1").css("display", "none")
                actionDrawerBtn.find("i").removeClass("fa-minus").addClass("fa-plus")
            }
        }
    }

    /**
     * Initialize hamburger and side menu functionality.
     */
    function initNav() {
        var hamburger = $("#hamburger")
        hamburger.on("click", toggleNavHandler)
    }

    /**
     * Initialize top drawer buttons functionality.
     */
    function initDrawerBtns() {
        var actionDrawerBtn = $(".min-action-nav-btn")
        actionDrawerBtn.on("click", toggleActionDrawer)
    }

    /**
     * Toggle side nav handler.
     */
    function toggleNavHandler() {
        var sideBar = $("nav ul");
        $(this).toggleClass('open');
        sideBar.toggleClass("opened")
    }

    /**
     * Toggle actions drawer handler.
     */
    function toggleActionDrawer() {
        var actionDrawer = $('.min-action-btns')
        if ($(this).find("i").hasClass("fa-minus")) {
            actionDrawer.css("height", "45px")
            $(".min-action-btns #btn2,.min-action-btns #btn1").css("display", "none")
            $(this).find("i").removeClass("fa-minus").addClass("fa-plus")
        } else {
            actionDrawer.css("height", "140px")
            $(".min-action-btns #btn2,.min-action-btns #btn1").css("display", "flex")
            $(this).find("i").removeClass("fa-plus").addClass("fa-minus")
        }
    }

    /**
     * execute GET request to get json file containing the cake tags colors.
     * @param {Loader} loader - loader instance.
     * @param {MessageModal} msgModal - message modal instance.
     */
    function getTagColorsFromJson(loader, msgModal) {
        var currentPath = window.location.href
        var basePath = currentPath.substr(0, currentPath.lastIndexOf("/"))
        $.ajax({
            type: 'get',
            url: `${basePath}/public/app-style.json`,
            success: function (data) {
                paintTags(data.tagColors)
            },
            error: () => {
                loader.stopLoader()
                msgModal.showMessageModal({
                    title: 'Server Error',
                    text: "Unable to load json file for tags colors",
                    color: 'alert',
                    timeout: 2000,
                })
            }
        });
    }

    /**
     * Apply tag colors obtained from json file.
     * @param {object} jsonTags - parsed json file containing the tags colors.
     */
    function paintTags(jsonTags) {
        var cakeTags = $(".badge")
        cakeTags.each(function () {
            var currentId = $(this).data("id")
            $(this).css({backgroundColor: jsonTags[currentId].color})
        })
    }

    return {
        MessageModal,
        initAvatar,
        initCardsFlipperAddon,
        initSearchForm,
        windowResizeHandler,
        initNav,
        initDrawerBtns,
        getTagColorsFromJson,
        Rating,
        CommentsController,
        UpdateProfileModal,
        cakeEdit,
        CakeMaker,
        Auth,
        Loader
    }


})(jQuery);
