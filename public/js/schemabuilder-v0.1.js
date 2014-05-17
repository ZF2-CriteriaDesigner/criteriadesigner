(function($) {

    var schema = {"": []};
    var selectType = ["id", "bool", "string", "list", "date", "entity", "collection"];
    var displayText = {"Class": "Add Class", "ClassName": "Class Name", "AddField": "Add Field", "Remove": "Remove", "FieldName": "Field Name", "DisplayName": "Display Name", "FieldType": "Field Type", "Options": "Options", "Empty": "Empty"};

    var SchemaBuilder = function(element, options) {
        this.element = $(element).filter('div').first();
		this.schema = options.schema || schema;
        this.displayText = options.displayText || $.fn.schemabuilder.displayText || displayText;
        this.selectType = selectType;
        this.idClass = 0;
        this.idField = 0;
        this.ParseSchema();
    };

	SchemaBuilder.prototype = {
		constructor: SchemaBuilder,

		ParseSchema: function () {
            var btnCreate = $('<a/>').addClass('btn btn-primary').attr('href', '#').html('<i class="icon-th-list icon-white"></i> ' + this.displayText.Class);
			btnCreate.on('click', $.proxy(this.ClickAddClass, this));
            this.main = $('<div/>').attr("id", "main").append([btnCreate, "<br>"]);
			this.element.append(this.main);
            var that = this;
            $.each(this.schema, function(key, val){
                that.AddClass(key, val);
            });
		},

        ClickAddClass: function (e) {
            e.preventDefault();
            this.AddClass();
        },
        
        AddClass: function (theClassName, schema) {
            this.idClass++;
            schema = schema ? schema : [];
            
            var divClass = $('<div/>').attr("id", "Class" + this.idClass).attr('data-idClass', this.idClass).addClass('classDef form-inline');
            
            var label = $('<label/>').attr('for', 'inputClassName' + this.idClass).text(this.displayText.ClassName);
            
            var inputClassName = $('<input/>').attr('id', 'inputClassName' + this.idClass).attr('name', 'inputClassName' + this.idClass).attr('type', 'text').addClass('inputClassName').attr('placeholder', 'ex: classEntity').val(theClassName);
            
            var btnAddField = this.BtnAddField(this.idClass);
            var btnRemoveClass = this.BtnRemoveClass(this.idClass);
            var actions = $('<div/>').addClass('actions btn-group').append(btnAddField, btnRemoveClass);
            
            var divFields = $('<div/>').addClass('content');
            var table = $('<table/>').addClass('tableField table');
            var header = $('<tr/>');
            header.append([$('<th/>').addClass('input-medium').text(this.displayText.FieldName), $('<th/>').addClass('input-medium').text(this.displayText.DisplayName),  $('<th/>').addClass('input-medium').text(this.displayText.FieldType),  $('<th/>').addClass('input-medium').text(this.displayText.Options),  $('<th/>') ]);
            table.append($('<thead/>').append(header));
            
            divFields.append(table);
            divClass.append([label, inputClassName, actions, divFields]);
            
            this.main.append(divClass);
            
            var that = this;
            schema.forEach(function (elt, i) {
                that.AddField(that.idClass, elt.field, elt.text, elt.type, elt.options || elt.entity || null);
            });
            
        },

        BtnAddField : function (idClass) {
			var a = $('<a/>').addClass('btn btn-primary').attr('href', '#').attr('data-idClass', idClass).html('<i class="icon-plus-sign icon-white"></i> ' + this.displayText.AddField);
			a.on('click', $.proxy(this.ClickAddField, this));
			return a;
        },

		BtnRemoveClass: function (idClass) {
			var a = $('<a/>').addClass('btn btn-primary').attr('href', '#').attr('data-idClass', idClass).html('<i class="icon-remove icon-white"></i> ' + this.displayText.Remove);
			a.on('click', $.proxy(this.ClickRemoveClass, this));
			return a;
		},

        ClickRemoveClass: function (e) {
            e.preventDefault();
            var target = $(e.currentTarget);
            var idClass = target.data('idclass');
            $('#Class' + idClass).remove();
        },

        ClickAddField: function (e) {
            e.preventDefault();
            var target = $(e.currentTarget);
            var idClass = target.data('idclass');
            this.AddField(idClass);
        },
        
        AddField: function (idClass, fieldName, displayName, listType, options) {
            this.idField++;
            listType = listType ? listType : 'string';

            var inputFieldName = $('<input/>').attr('id', 'inputFieldName' + this.idField).attr('name', 'inputFieldName' + this.idField).attr('type', 'text').addClass('inputFieldName input-block-level').attr('placeholder', 'ex: levelClass').val(fieldName);
            
            var inputDisplayName = $('<input/>').attr('id', 'inputDisplayName' + this.idField).attr('name', 'inputDisplayName' + this.idField).attr('type', 'text').addClass('inputDisplayName input-block-level').attr('placeholder', 'ex: Level').val(displayName);
            
            var selectListType = $('<select/>').attr('id', 'selectListType' + this.idField).addClass('input-medium').attr('data-idfield', this.idField);
            this.selectType.forEach(function (e, i) { var opt = $('<option/>').text(e); selectListType.append(opt); });
            selectListType.val(listType);
			selectListType.on('change', $.proxy(this.ChangeSelectListType, this));
            
            var inputOptions = $('<input/>').attr('id', 'inputOptions' + this.idField).attr('name', 'inputOptions' + this.idField).attr('type', 'text').addClass('inputOptions input-block-level').attr('disabled', 'disabled').attr('placeholder', this.displayText.Empty).val(options);
            
            if (listType == "list" || listType == "entity" || listType == "collection")
                inputOptions.removeAttr('disabled');
            if (listType == "list")
                inputOptions.val(options.join(", "));

            var table = $('.tableField', '#Class' + idClass);
            var btnRemoveField = this.BtnRemoveField(this.idField);
            var tr = $('<tr/>').attr('id', 'idField' + this.idField).attr('data-idfield', this.idField).addClass('fields').append([$('<td/>').append(inputFieldName), $('<td/>').append(inputDisplayName), $('<td/>').append(selectListType), $('<td/>').append(inputOptions), $('<td/>').append(btnRemoveField)]);
            table.append(tr);
            
        },
        
        ChangeSelectListType: function (e) {
            e.preventDefault();
            var target = $(e.currentTarget);
            var idField = target.data('idfield');
            var val = target.val();
            if (val == "list")
                $('#inputOptions' + idField).removeAttr('disabled').attr('placeholder', 'ex: Level1, Level2, Level3');
            else if (val == "entity" || val == "collection")
                $('#inputOptions' + idField).removeAttr('disabled').attr('placeholder', 'ex: studentEntity');
            else
                $('#inputOptions' + idField).attr('placeholder', this.displayText.Empty).attr('disabled', 'disabled');
        },

		BtnRemoveField: function (idField) {
			var btn = $('<button/>').addClass('btn btn-primary btn-mini').attr('href', '#').attr('data-idfield', idField).html('<i class="icon-remove icon-white"></i>');
			btn.on('click', $.proxy(this.ClickRemoveField, this));
			return btn;
		},

        ClickRemoveField: function (e) {
            e.preventDefault();
            var target = $(e.currentTarget);
            var idField = target.data('idfield');
            console.log('#idField' + idField);
            $('#idField' + idField).remove();
        },
        
        ParseClasses: function () {
            var schema = {};
            var classes = $('.classDef');
            classes.each(function (i, e) {
                var subSchema = [];
                var idClass = $(e).data('idclass');
                var theClassName = $('#inputClassName' + idClass).val();
                
                var fields = $('.fields', $(e));
                fields.each(function (td_i, td_e) { 
                    var metaData = {};
                    var idField = $(td_e).data("idfield");
                    metaData.field = $('#inputFieldName' + idField).val();
                    metaData.type = $('#selectListType' + idField).val();
                    metaData.text = $('#inputDisplayName' + idField).val();
                    if (metaData.type == "list")
                        metaData.options = $('#inputOptions' + idField).val().split(',').map(function(i) {return i.trim();});
                    if (metaData.type == "entity" || metaData.type == "collection")
                        metaData.entity = $('#inputOptions' + idField).val();
                        
                    subSchema.push(metaData);
                });
                schema[theClassName] = subSchema;
            });
            
            return schema;
        },
        
        ExportData: function () {
            return this.ParseClasses();
        },
        
		destroy: function() {
			this.main.remove();
			delete this.element.data().schemabuilder;
			return true;
		}


    };

    $.fn.schemabuilder = function(option) {
        var args = Array.prototype.slice.call(arguments, 0);
        var $this = $(this), SB = $this.data('schemabuilder'), options = option;
        if (args.length == 0)
            options = {};

        if (!SB)
            return ($this.data('schemabuilder', new SchemaBuilder($this, options)));
        else if (args[0] == "destroy")
            return SB.destroy.apply(SB);
        else if (args[0] == "export")
            return SB.ExportData.apply(SB);
    };

})(jQuery);