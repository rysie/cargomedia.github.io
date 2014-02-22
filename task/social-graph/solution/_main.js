/**
 * Another social graph solution
 * backbone.js / jQuery approach
 * 
 * @author Marcin Maciński
 * 
 */



/**
 * Helper: get user ID from classes string
 * 
 * @param Object 
 * @return int
 * 
 */
jQuery.fn.extend({
    getUid: function() {
        var classes = $(this).attr("class").replace("  ", " ");
        var out = false;
        classes.split(" ").map(function(cls) {
            if ((cls.length) && (cls.substr(0, 4) === "uid_")) {
                var uid = cls.replace("uid_", "");
                if (uid && uid.length)
                    out = uid;
            }
        });
        return out;
    }
});


$(function() {

    /**
     * User model definition
     * with default empty values
     * 
     * Should never happen to see defaults 
     * as data provided is sanitized during json parsing
     */

    var userModel = Backbone.Model.extend({
        defaults: {
            "id": null,
            "firstName": null,
            "surname": null,
            "age": null,
            "gender": null,
            "friends": []
        }
    });

    /**
     * All users collection 
     * @param void
     */
    var allUsersColl = Backbone.Collection.extend({
        model: userModel,
        url: "ajax.php/?users=all",
        initialize: function() {
            this.fetch({
                success: function(data) {
                    var auv = new tplUsersView({collection: data, el: "#people"});
                    auv.render();
                }
            });
        }
    });

    /**
     * Friends of friends call
     * @param int uid 
     * 
     */
    var suggestedFriends = Backbone.Collection.extend({
        model: userModel,
        initialize: function(params) {
            this.url = "ajax.php/?users=fof&fof=" + params.uid;
            this.fetch({
                success: function(data) {
                    var auv = new tplUsersView({collection: data, el: "#fof"});
                    auv.render();
                }
            });
        }
    });

    /**
     * Render view using underscore template provided within index.html
     * Used for both all users and suggested friends list
     * 
     * @param void
     */
    var tplUsersView = Backbone.View.extend({
        template: _.template($("#users-template").html()),
        el: "#people",
        initialize: function() {
            _.bindAll(this, 'render');
        },
        render: function() {
            if (this.collection.length) {
                var output = this.template({'users': this.collection.toJSON()});
                $(this.el).append(output);
                $(this.el).html(output);
            } else {
                $(this.el).html("<h2>No results</h2>");
            }
        }
    });

    var allUsers = new allUsersColl();


    /**
     * Show friends on click event
     * Shows/hides user boxes
     * Makes GET call to retrieve suggested friends
     * 
     */
    $("#people").on("click", ".person", function() {
        $("#suggested_friends").slideDown();

        if (!$(this).hasClass("friend_highlight") &&
                !$(this).hasClass("myself_highlight")) {

            $(this).addClass("myself_highlight");
            $("#reset").slideDown();

            $(this).attr("class").split(" ").map(function(cls) {
                if ((cls.length) && (cls.substr(0, 4) === "uid_")) {
                    var uid = cls.replace("uid_", "");
                    $(".f_" + uid).addClass("friend_highlight");
                }
            });

            var suggested = new suggestedFriends({uid: $(this).getUid()});

            $("#people .person").each(function() {
                if (!$(this).hasClass("friend_highlight")
                        && !$(this).hasClass("myself_highlight"))
                    $(this).slideUp();
            });
        }
    });


    /**
     * Reset button actions
     * @param void
     * 
     */
    $("#reset").on("click", function() {
        $("#all_users .person").slideDown();
        $("#suggested_friends").slideUp();
        $(".friend_highlight").removeClass("friend_highlight");
        $(".myself_highlight").removeClass("myself_highlight");
        $("#reset").slideUp();
    });


});

