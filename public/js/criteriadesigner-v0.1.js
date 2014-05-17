(function($) {

    var schema = {
        "classEntity":[
            {"field":"labelClass","type":"string","text":"Label"},
            {"field":"levelClass","type":"list","text":"Level","options":["Level 1","Level 2","Level 3"]},
            {"field":"sectionClass","type":"list","text":"Section","options":["Section 1","Section 2","Section 3"]},
            {"field":"studentList","type":"collection","text":"Student (count)","entity":"studentEntity"}],
        "studentEntity":[
            {"field":"lastnameStudent","type":"string","text":"Last Name"},
            {"field":"firstnameStudent","type":"string","text":"First Name"},
            {"field":"birthdayStudent","type":"date","text":"Birthday"},
            {"field":"genderStudent","type":"list","text":"Gender","options":["Male","Female"]},
            {"field":"classEntity","type":"entity","text":"Class","entity":"classEntity"}]
    };

	var categoriesConditions = {
			"id": [ "connected", "not connected", "equal", "not equal"],
			"bool" : [ "true", "false"],
			"int": [ "equal", "not equal", "greater than strict", "greater than or equal", "less than strict", "less than or equal", "between", "not between", "start with", "not start with", "contains", "not contains",
					"end with", "not end with", "in", "not in", "empty", "not empty"],
			"string": [ "equal", "not equal", "start with", "not start with", "contains", "not contains",
					"end with", "not end with", "in", "not in", "empty", "not empty"],
			"list": ["equal", "not equal", "start with", "not start with", "contains", "not contains",
					"end with", "not end with","in", "not in", "empty", "not empty"],
			"date": [ "equal", "not equal", "greater than strict", "greater than or equal", "less than strict", "less than or equal",
					"between", "not between", "empty", "not empty",
					"today", "yesterday", "tomorrow",
					"last week", "earlier this week", "this week", "later this week", "next week",
					"last month", "earlier this month", "this month", "later this month", "last month",
					"earlier this year", "this year", "later this year"],
			"collection" : [ "exist", "not exist", "all", "equal", "not equal", "greater than strict", "greater than or equal", "less than strict", "less than or equal", "between", "not between"]
	};

	var parameterConditions =
	{
		"id": [[0, 1], {
			"input": "no",
			"else": {
				"input": "text"
			}
		}],
		"bool": {"input": "no"},
		"int": [[6, 7], {
			"input": "twotext",
			"else": [[14, 15], {
				"input": "tags",
				"else" : [[16, 17], {
					"input": "no",
					"else": {
						"input": "text"
					}
				}]
			}]
		}],
		"string": [[8, 9], {
			"input": "tags",
			"else": [[10, 11], {
				"input": "no",
				"else": {
					"input": "text"
				}
			}]
		}],
		"list": [[0, 1], {
			"input": "select",
			"else": [[8, 9], {
				"input": "multiselect",
				"else": [[10, 11], {
					"input": "no",
					"else": {
						"input": "text"
					}
				}]
			}]
		}],
		"date": [[0, 5], {
			"input": "text",
			"else": [[6,7], {
				"input": "twotext",
				"else": {
					"input": "no"
				}
			}]
		}],
		"collection": [[3, 8], {
			"input": "text",
			"else": [[9, 10], {
				"input": "twotext",
				"else": {
					"input": "no"
				}
			}]
		}]
	};

	var displayText = {"AND": "AND", "OR": "OR", "NAND": "NAND", "NOR": "NOR", "Group": "Group", "Element": "Element", "Remove": "Remove"};

	var CriteriaDesigner = function(element, options) {
		this.element = $(element).filter('div').first();
		this.schema = options.schema || schema;
		this.criteria = options.criteria || {"entity": 'classEntity', "selectOperator": "AND", "comparisons": []};
		this.displayText = options.displayText || $.fn.criteriadesigner.displayText || displayText;
		this.categoriesConditions = $.fn.criteriadesigner.categoriesConditions || categoriesConditions;
		this.parameterConditions = parameterConditions;
		this.idGroup = 0;
		this.idSelect = 0;
		this.idElement = 0;
		this.idCascade = 0;
		this.idInput = 0;

		this.parseCriteria();
	};

	CriteriaDesigner.prototype = {
		constructor: CriteriaDesigner,

		parseCriteria: function() {
			this.mainGroup = this.addGroup('', this.criteria.entity, false);
			this.element.append(this.mainGroup);
			this.fillGroup(this.mainGroup.attr('id'), this.criteria);
		},

		addGroup: function(parent, entity, btnDelete) {
			var canDelete = typeof btnDelete == "boolean" ? btnDelete : true;
			var group = 'Group' + this.idGroup;
			this.idGroup++;
			var groupClass = parent != '' ? 'subgroup' : 'group';
			var box = $('<div/>').addClass('cd-box').addClass(groupClass).addClass("comparison" + parent).attr('id', group).attr('data-entity', entity);
			var hr = $('<hr/>').addClass('hr-group');

			var boxContent = $('<div/>').addClass('cd-box-content').attr('id', 'content' + group);

			var actions = $('<div/>').addClass('actions btn-group');
			var _btnElement = this.btnAddGroup(group);
			var _btnAddGroup = this.btnAddElement(group);
			var _btnRmove = canDelete ? this.btnRemoveGroup(group) : null;
			actions.append([ _btnElement, _btnAddGroup, _btnRmove]);

			var selectOperator = this.createSelectOperator(group);
			var title = $('<div/>').addClass('title').html(selectOperator);
			var boxTitle = $('<div/>').addClass('cd-box-title');
			boxTitle.append([title, actions, hr]);

			if (canDelete)
				hr.addClass('hr-group');
			else
				hr.addClass('hr-group-hidden');

			box.append([boxTitle, boxContent]);

			return box;
		},

		btnAddGroup: function(parent) {
			var a = $('<a/>').addClass('btn btn-primary').attr('href', '#').attr('data-group', parent).html('<i class="icon-th-list icon-white"></i> ' + this.displayText.Group);
			a.on('click', $.proxy(this.clickAddGroup, this));
			return a;
		},

		btnAddElement: function (parent) {
			var entity = $('#' + parent).data('entity');
			var a = $('<a/>').addClass('btn btn-primary').attr('href', '#').attr('data-entity', entity).attr('data-group', parent).html('<i class="icon-plus-sign icon-white"></i> ' + this.displayText.Element);
			a.on('click', $.proxy(this.clickAddElement, this));
			return a;
		},

		btnRemoveGroup: function(parent) {
			var a = $('<a/>').addClass('btn btn-primary').attr('href', '#').attr('data-group', parent).html('<i class="icon-remove icon-white"></i> ' + this.displayText.Remove);
			a.on('click', $.proxy(this.clickRemoveGroup, this));
			return a;
		},

		createSelectOperator: function(parent) {
			var selectCondition = $('<select/>').addClass('input-medium').addClass('selectOperator' + parent);
			var optionAND = $('<option/>').attr('value', 'AND').html(this.displayText.AND);
			var optionOR = $('<option/>').attr('value', 'OR').html(this.displayText.OR);
			var optionNAND = $('<option/>').attr('value', 'NAND').html(this.displayText.NAND);
			var optionNOR = $('<option/>').attr('value', 'NOR').html(this.displayText.NOR);
			selectCondition.append([optionAND, optionOR, optionNAND, optionNOR]);

			return selectCondition;
		},

		clickAddGroup: function(e) {
			e.preventDefault();
			var target = $(e.currentTarget);
			var parent = target.data('group');
			var entity = $('#' + parent).data('entity');
			var oGroup = this.addGroup(parent, entity);
			$('#content' + parent).append(oGroup);
		},

		clickRemoveGroup: function (e) {
			e.preventDefault();
			var target = $(e.currentTarget);
			var parent = target.data('group');
			this.removeDTPS2('#' + parent);
			$('#' + parent).remove();
		},

		clickAddElement: function(e) {
			e.preventDefault();
			var target = $(e.currentTarget);
			var parent = target.data('group');
			var entity = $('#' + parent).data('entity');

			var oElement = this.addElement(parent, entity);
			$('#content' + parent).first().append(oElement);
		},

		addElement: function(parent, entity) {
			var element = 'Element' + this.idElement;
			var div = $('<div/>').addClass('element').addClass('comparison' + parent).attr('id', element).attr('data-entity', entity).attr('data-group', parent).css('clear', 'both');
			this.idElement++;
			var divContent = $('<div/>').addClass("mainContent");
			var divInputField = $('<div/>').addClass('field elementContent').attr('id', 'inputField' + element);
			var divCondition = $('<div/>').addClass('condition elementContent').attr('id', 'condition' + element);
			var divParameter = $('<div/>').addClass('parameter elementContent').attr('id', 'parameter' + element);
			var cascadeInputField = this.cascadeSelectBase(element, entity);

			var selectCondition = this.addSelectCondition(element, 0, entity);
			divCondition.html(selectCondition);

			var parameter = this.addParameter(selectCondition);
			divParameter.html(parameter);

			divInputField.append(cascadeInputField);
			var btnRemove = this.btnRemoveElement(element);
			divContent.append($('<hr/>').addClass('hr-element'));
			divContent.append([btnRemove, divInputField, divCondition, divParameter]);
			div.append(divContent);

			return div;
		},

		btnRemoveElement: function(parent) {
			var btnRemove = $('<button/>').addClass("deleteElement btn btn-primary btn-mini").attr('data-element', parent).append('<i class="icon-remove icon-white"></i>');
			btnRemove.on('click', $.proxy(this.clickRemoveElement, this));
			return btnRemove;
		},

		clickRemoveElement: function(e) {
			e.preventDefault();
			var target = $(e.currentTarget);
			var parent = target.data('element');
			this.removeDTPS2('#' + parent);
			$('#' + parent).remove();
		},

		cascadeSelectBase: function(parent, entity) {
			var divCascade = $('<div/>').addClass('btn-group');
			var inputMain = $('<input/>').addClass('input-medium dropdown-toggle inputField');
			inputMain.attr('type', 'text').attr('readonly', true).attr('name', 'input'+parent).attr('data-toggle', 'dropdown');
			inputMain.attr('data-group', parent).attr('data-entity-pos', 0);
			inputMain.on('click', $.proxy(this.repositionUL, this));

			var uList = this.cascadeSelect(parent, entity, '', '');
			var entitySchema = this.schema[entity];
			inputMain.attr('data-entity', entity).attr('value', entitySchema[0].text).attr('data-field', entitySchema[0].field);
			divCascade.append([inputMain, uList]);

			return divCascade;
		},

		cascadeSelect: function (parent, entity, field, text) {
			var uList = $('<ul/>').addClass('dropdown-menu cascadeSelect pull-left');

			var entitySchema = this.schema[entity];
			var that = this;
			entitySchema.forEach(function (e, i) {
				if (e.type != 'entity') {
					var li = $('<li/>');
					var anchor = $('<a/>').attr('href', '#').attr('data-element', parent).attr('data-entity', entity).attr('data-entity-pos', i).attr('data-field', e.field).attr('data-text', text + e.text).html(e.text);
					anchor.attr('data-field-complex', field + e.field);
					anchor.on('click', $.proxy(that.clickFieldDropdownSelect, that));
					li.append(anchor);
					uList.append(li);
				}
				else {
					var li = $('<li/>').addClass('dropdown-submenu');
					var anchor = $('<a/>').attr('href', '#').addClass('dropdown-toggle').attr('data-toggle', 'dropdown').html(e.text);
					li.append(anchor);
					var joinEntity = e.entity ? e.entity : e.field;
					var ulSub = that.cascadeSelect(parent, joinEntity, field + e.field + '::', text + e.text + '::');
					li.append(ulSub);
					li.on('mouseenter', $.proxy(that.repositionSubUL, that));
					uList.append(li);
				};
			});

			return uList;
		},

		addSelectCondition: function(element, entityPos, entity) {
			var entitySchema = this.schema[entity];
			var opt = entitySchema[entityPos];

			var type = opt.type;
			var conditions = this.categoriesConditions[type];
			var select = $('<select/>').addClass("input-medium").attr('data-element', element).attr('data-entity', entity).attr('data-entity-pos', entityPos).attr('data-type', type);
			conditions.forEach(function (e, i) {
				var option = $('<option/>');
				option.attr('value', i).html(e);
				select.append(option);
			});

			select.on('change', $.proxy(this.clickSelectCondition, this));
			return select;
		},

		clickFieldDropdownSelect: function (e) {
			e.preventDefault();
			var target = $(e.currentTarget);
			var element = target.data('element');
			var text = target.data('text');
			var entityPos = target.data('entity-pos');
			var entity = target.data('entity');
			var field = target.data('field');
			var fieldComplex = target.data('field-complex');

			this.updateSelectCondition(element, text, entity, entityPos, field, fieldComplex);
		},

		updateSelectCondition: function(element, text, entity, entityPos, field, fieldComplex) {
			var input = $('input[name="input'+element+'"]');
			input.attr('value', text).attr('data-entity-pos', entityPos).attr('data-field', field).attr('data-field-complex', fieldComplex).attr('data-entity', entity);

			var select = this.addSelectCondition(element, entityPos, entity);
			$('#condition' + element).html(select);

			var parameter = this.addParameter(select);
			this.removeDTPS2('#parameter' + element);
			$('#parameter' + element).html(parameter);
			this.prepareParameter(parameter);

			var entitySchema = this.schema[entity];
			var opt = entitySchema[entityPos];
			var type = opt.type;
			$('.subContent', '#' + element).remove();
			if (type == 'collection')  {
				var divSubContent = $('<div/>').addClass('subContent');
				var joinEntity = opt.entity ? opt.entity : opt.field;
				var oGroupe = this.addGroup(element, joinEntity, false);
				divSubContent.append(oGroupe);
				$('#' + element).append(divSubContent);
			}
		},

		filter: function (o, pos) {
			if (jQuery.isArray(o)) {
				var range = o[0];
				if (pos >= range[0] && pos <= range[1]) {
					return this.filter(o[1], pos);
				}
				else
					return this.filter(o[1]["else"], pos);
			}
			else if (typeof o === 'object')
				return o.input;

			return false;
		},

		inputParameter: function(type, pos) {
			return this.filter(this.parameterConditions[type], pos);
		},

		addParameter: function(select) {
			var selectPos = select.val();
			var element = select.data('element');
			var entityPos = select.data('entity-pos');
			var type = select.data('type');
			var entity = select.data('entity');
			var entitySchema = this.schema[entity];
			var opt = entitySchema[entityPos];

			var inputType = this.inputParameter(type, selectPos);
			switch(inputType)
			{
				case 'text':
					var input = $('<input/>').attr('type', 'text').addClass('input-param input-medium');
					input.attr('data-input-type', 'text').attr('data-value-type', type).attr('id', 'input-' + type + this.idInput++);
					return input;
				case 'tags':
					var input = $('<input/>').attr('type', 'text').addClass('input-param input-medium');
					input.attr('data-input-type', 'tags').attr('data-value-type', type).attr('id', 'input-' + type + this.idInput++);
					return input;
				case 'twotext':
					var input1 = $('<input/>').attr('type', 'text').addClass('input-param input-medium');
					input1.attr('data-input-type', 'text').attr('data-value-type', type).attr('id', 'input-' + type + this.idInput++);
					var input2 = $('<input/>').attr('type', 'text').addClass('input-param input-medium');
					input2.attr('data-input-type', 'text').attr('data-value-type', type).attr('id', 'input-' + type + this.idInput++);
					return [input1, $('<b/>').css('padding', '5px').text(this.displayText.AND.toLowerCase()), input2];
				case 'select':
					var select = $('<select/>').addClass('input-param input-medium').attr('id', 'input-' + type + this.idInput++);
					select.attr('data-input-type', 'select').attr('data-value-type', type).attr('data-entity', entity).attr('data-entity-pos', entityPos);
					return select;
				case 'multiselect':
					var select = $('<select/>').addClass('input-param input-medium').attr('id', 'input-' + type + this.idInput++);
					select.attr('data-input-type', 'multiselect').attr('data-value-type', type).attr('data-entity', entity).attr('data-entity-pos', entityPos);
					return select;
				default: return null;
			}
		},
		clickSelectCondition: function(e) {
			e.preventDefault();
			var target = $(e.currentTarget);
			var element = target.data('element');
			var parameter = this.addParameter(target);
			this.removeDTPS2('#parameter' + element);
			$('#parameter' + element).html(parameter);
			this.prepareParameter(parameter);
		},

		prepareParameter: function(parameter) {
			if (!parameter)
				return;

			if (jQuery.isArray(parameter)) {
				var input1 = parameter[0];
				var input2 = parameter[2];
				var type = input1.data('value-type');
				if (type == 'date') {
					input1.datetimepicker({minView: 2, maxView: 3, startView: 2, autoclose: true, format: 'yyyy-mm-dd', dateTimePickerClass: 'criteriadesigner'});
					input2.datetimepicker({minView: 2, maxView: 3, startView: 2, autoclose: true, format: 'yyyy-mm-dd', dateTimePickerClass: 'criteriadesigner'});
				}
			}
			else {
				var input = parameter;
				var valueType = input.data('value-type');
				if (valueType == 'date') {
					input.datetimepicker({minView: 2, maxView: 3, startView: 2, autoclose: true, format: 'yyyy-mm-dd', dateTimePickerClass: 'criteriadesigner'});
				}
				else {
					var inputType = input.data('input-type');
					if (inputType == 'tags') {
						input.attr('type', 'hidden');
						input.parent().css('width', 'auto').css('min-width', '160px');
						input.select2({tags: [], width: '100%', containerCssClass: "cd", dropdownCssClass: "cd"});
					}
					if (inputType == 'select' || inputType == 'multiselect') {
						var entity = input.data('entity');
						var entityPos = input.data('entity-pos');
						var field = this.schema[entity][entityPos];
						if (field.options) {
							field.options.forEach(function (e, i) {
								var option = $('<option/>').attr('value', e).html(e);
								input.append(option);
							});
							if (inputType == 'multiselect')
								input.attr('multiple', true).parent();

							input.parent().css('min-width', '160px');
							input.select2({minimumResultsForSearch: 15, width: '100%', containerCssClass: "cd", dropdownCssClass: "cd"});
						}
					}
				}
			}
		},

		removeDTPS2: function(parent) {
			$('input[data-value-type="date"]', parent).each(function() { $(this).datetimepicker('remove'); });
			$('input[data-input-type="tags"]', parent).each(function() { $(this).select2('destroy'); });
			$('select[data-value-type="liste"]', parent).each(function() { $(this).select2('destroy'); });
		},

		reposition: function(ul, parent, percent, nb) {
			var offset = parent.offset(),
				parentHeight = parent.outerHeight(false),
				dropdownHeight = ul.outerHeight(false),
				$window = $(window),
				screenXmin = $window.scrollTop();
				screenXmax = screenXmin + $window.height(),
				parentXmin = offset.top,
				parentXmax = parentXmin + parentHeight,
				enoughSpaceBelow = parentXmax + dropdownHeight <= screenXmax,
				enoughSpaceAbove = (parentXmin - dropdownHeight) >= screenXmin,
				overflow1 = overflow2 = 0;

			if (nb != 0) {
				liHeight = ul.children().first().height();
				overflow1 = Math.round((parentXmin - dropdownHeight - screenXmin) / liHeight) + 1;
				overflow2 = Math.round((screenXmax - (parentXmax + dropdownHeight)) / liHeight) + 1;

			}

			if (!enoughSpaceBelow && enoughSpaceAbove) {
				if (overflow1 < 0)
					percent = 100 * (overflow1 - 1) + "%";

				ul.css({"top": "auto", "bottom": percent});
				if (parent.hasClass("dropdown-submenu"))
					ul.css("margin-bottom", "-6px");
			}
			else {
				if (overflow2 < 0)
					percent = 100 * (overflow2 - 1) + "%";

				ul.css({"top": percent, "bottom": "auto"});
			}
		},

		repositionUL: function (e) {
			var target = $(e.currentTarget);
			var o = $('ul', target.parent());
			var parent = $('input', o.parent()).first();
			this.reposition(o, target, "100%", 0);
		},

		repositionSubUL: function (e) {
			var target = $(e.currentTarget);
			var o = $('ul', target).first();
			var nb = o.children().length;
			var parent = o.parent();
			this.reposition(o, target, "0", nb);
		},

		parseGroup: function(id) {
			var results = { };
			var entity = $('#' + id).data('entity');
			var selectOperator = $('.selectOperator' + id);
			results.entity = entity;
			results.selectOperator = selectOperator.val();
			var oComparison = $('.comparison' + id);
			results.comparisons = [];
			var that = this;
			oComparison.each(function () {
				if ($(this).hasClass('element'))
					results.comparisons.push({"type": "simple", "content": that.parseElement($(this).attr('id'))});
				else
					results.comparisons.push({"type": "group", "content": that.parseGroup($(this).attr('id'))});

			});

			return results;
		},

		parseElement: function(id) {
			var results = {};
			var oElement = $('#' + id);
			var field = $('#inputField' + id);
			var condition = $('#condition' + id);
			var parameter = $('#parameter' + id);

			var fieldInput = $('input', field);
			results.field = {"name": fieldInput.data('field'), "complexName": fieldInput.data('field-complex'), "text": fieldInput.attr('value'), "pos":fieldInput.data('entity-pos'), "entityField": fieldInput.data('entity')};

			var selectCondition = $('select', condition);
			var conditionType = selectCondition.data('type');
			var conditionPos = selectCondition.val();
			results.condition = {"type": conditionType, "pos": conditionPos, "text": this.categoriesConditions[conditionType][conditionPos]};

			results.parameter = [];
			$('.input-param', parameter).each(function () {
				if ($(this).data('input-type')) {
					results.parameter.push($(this).val());
				}
			});

			if (conditionType == 'collection') {
				var content = $('.comparison' + id, oElement);
				results.subCriteria = this.parseGroup(content.attr('id'));
			};

			return results;
		},

		fillGroup: function(parent, data) {
			var entity = $('#' + parent).data('entity');
			var selectOperator = $('.selectOperator' + parent);
			selectOperator.val(data.selectOperator);
			this.fillElement(parent, entity, data);
		},

		fillElement: function(group, entity, data) {
			var n_entity = entity;
			var entitySchema = this.schema[entity];
			var that = this;
			console.log(data);
			data.comparisons.forEach(function (e, i) {
				var content = e.content;
				if (e.type == "simple") {
					var oElement = that.addElement(group, n_entity);
					var element = oElement.attr('id');
					$('#content' + group).first().append(oElement);

					that.updateSelectCondition(element, content.field.text, content.field.entityField, content.field.pos, content.field.name, content.field.complexName);
					that.removeDTPS2(element);

					var selectCondition = that.addSelectCondition(element, content.field.pos, content.field.entityField);
					var divCondition = $('#condition' + element).html(selectCondition);
					selectCondition.attr('data-entity-pos', content.field.pos).attr('data-type', that.schema[content.field.entityField][content.field.pos]['type']).val(content.condition.pos);
					that.removeDTPS2('#parameter' + element);

					var parameter = that.addParameter(selectCondition);
					$('#parameter' + element).html(parameter);
					that.prepareParameter(parameter);

					that.fillParameter(parameter, content);

					if (content.subCriteria) {
						$('.subContent', '#' + element).remove();
						var divSubContent = $('<div/>').addClass('subContent');
						var oSubGroup = that.addGroup(element, content.subCriteria.entity, false);
						divSubContent.html(oSubGroup);
						$('#' + element).append(divSubContent);
						var subGroup = oSubGroup.attr('id');
						that.fillGroup(subGroup, content.subCriteria);
					}
				}
				else {
					var oSubGroup = that.addGroup(group, n_entity);

					$('#content' + group).append(oSubGroup);
					var subGroup = oSubGroup.attr('id');
					that.fillGroup(subGroup, content);
				}
			});
		},

		fillParameter: function(parameter, e) {

			if (jQuery.isArray(parameter)) {
				$(parameter[0]).val(e.parameter[0]);
				$(parameter[2]).val(e.parameter[1]);
			}
			else if (e.parameter.length > 0) {
				var parameterValue = e.parameter[0];
				switch(parameter.data('input-type'))
				{
					case 'select': parameter.select2("data", {"id": parameterValue, "text": parameterValue}); break;
					case 'multiselect':
						var values = [];
						parameterValue.forEach(function (e, i) {
							values.push({"id": e, "text": e});
						});
						parameter.select2("data", values);
						break;
					case 'tags':
						var values = [];
						var tags = parameterValue.split(",");;
						tags.forEach(function (e, i) {
							values.push({"id": e, "text": e});
						});
						parameter.select2("data", values);
						break;
					default: parameter.val(e.parameter[0]); break;
				}
			}

		},

		exportData: function() {
			var results = this.parseGroup(this.mainGroup.attr("id"));
			return results;
		},

		destroy: function() {
			this.mainGroup.remove();
			delete this.element.data().criteriadesigner;
			return true;
		}

	};

	$.fn.criteriadesigner = function (option) {
		var args = Array.prototype.slice.call(arguments, 0);
		var $this = $(this), cd = $this.data('criteriadesigner'), options = option;
		if (args.length == 0)
			options = {};

		if (!cd)
			return ($this.data('criteriadesigner', new CriteriaDesigner($this, options)));
		else if (args[0] == "destroy")
			return cd.destroy.apply(cd);
		else if (args[0] == "export")
			return cd.exportData.apply(cd);
	};

})(jQuery);