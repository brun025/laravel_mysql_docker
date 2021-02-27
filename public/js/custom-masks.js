/**
 * ========================================================================
 * Inputmask
 * ========================================================================
 */
$(document).on("focus", ".decimal-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 6,
        'digits': 2,
        'digitsOptional': true,
        'groupSeparator': ".",
        'radixPoint': ",",
        'autoGroup': true,
        'allowMinus': true,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        // Fix decimal point on unmask
        'onUnMask': function(maskedValue, _unmaskedValue) {
            var x = maskedValue.split(',');
            if (x[1] === undefined) return x[0].replace(/\./g, '');
            else return x[0].replace(/\./g, '') + '.' + x[1];
        },
        // Fix for starting with negative decimal
        'onKeyDown': function(event, buffer, _caretPos, _opts) {
            var currentValue = buffer.length == 2 ? buffer[0] : "";
            if (currentValue === "-" && (event.key === "Decimal" || event.key === ".")) $(event.currentTarget).val('-0..');
        }
    });
});
$(document).on("focus", ".float-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 6,
        'digits': 2,
        'digitsOptional': false,
        'groupSeparator': ".",
        'radixPoint': ",",
        'autoGroup': true,
        'allowMinus': false,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        'unmaskAsNumber': true,
        // Fix for starting with negative decimal
        'onKeyDown': function(event, buffer, _caretPos, _opts) {
            var currentValue = buffer.length == 2 ? buffer[0] : "";
            if (currentValue === "-" && (event.key === "Decimal" || event.key === ".")) $(event.currentTarget).val('-0..');
        }
    });
});
$(document).on("focus", ".double-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 14,
        'digits': 2,
        'digitsOptional': true,
        'groupSeparator': ".",
        'radixPoint': ",",
        'autoGroup': true,
        'allowMinus': true,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        'unmaskAsNumber': true,
        // Fix for starting with negative decimal
        'onKeyDown': function(event, buffer, _caretPos, _opts) {
            var currentValue = buffer.length == 2 ? buffer[0] : "";
            if (currentValue === "-" && (event.key === "Decimal" || event.key === ".")) $(event.currentTarget).val('-0..');
        }
    });
});
$(document).on("focus", ".money-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'prefix': 'R$ ',
        'rightAlign': false,
        'integerDigits': 14,
        'digits': 2,
        'digitsOptional': false,
        'groupSeparator': ".",
        'radixPoint': ",",
        'autoGroup': true,
        'allowMinus': true,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        'unmaskAsNumber': true,
        // Fix for starting with negative decimal
        'onKeyDown': function(event, buffer, _caretPos, _opts) {
            var currentValue = buffer.length == 2 ? buffer[0] : "";
            if (currentValue === "-" && (event.key === "Decimal" || event.key === ".")) $(event.currentTarget).val('-0..');
        }
    });
});
$(document).on("focus", ".percentage-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 3,
        'min': 0,
        'max': 100,
        'digits': 2,
        'digitsOptional': true,
        'groupSeparator': ".",
        'radixPoint': ",",
        'autoGroup': true,
        'allowMinus': false,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        // Fix decimal point on unmask
        'onUnMask': function(maskedValue, _unmaskedValue) {
            var x = maskedValue.split(',');
            if (x[1] === undefined) return x[0].replace(/\./g, '');
            else return x[0].replace(/\./g, '') + '.' + x[1];
        }
    });
});
$(document).on("focus", ".integer-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 6,
        'digits': 0,
        'groupSeparator': ".",
        'autoGroup': true,
        'allowMinus': false,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        // Fix for starting with negative decimal
        'onKeyDown': function(event, buffer, _caretPos, _opts) {
            var currentValue = buffer.length == 2 ? buffer[0] : "";
            if (currentValue === "-" && (event.key === "Decimal" || event.key === ".")) $(event.currentTarget).val('-0..');
        }
    });
});
$(document).on("focus", ".zero-to-ten-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 2,
        'min': 0,
        'max': 10,
        'digits': 0,
        'groupSeparator': ".",
        'autoGroup': true,
        'allowMinus': false,
        'removeMaskOnSubmit': true,
        'autoUnmask': true
    });
});
$(document).on("focus", ".latitude-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 2,
        'min': -90,
        'max': 90,
        'digits': 8,
        'digitsOptional': true,
        'groupSeparator': ".",
        'radixPoint': ",",
        'autoGroup': true,
        'allowMinus': true,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        // Fix decimal point on unmask
        'onUnMask': function(maskedValue, _unmaskedValue) {
            var x = maskedValue.split(',');
            if (x[1] === undefined) return x[0].replace(/\./g, '');
            else return x[0].replace(/\./g, '') + '.' + x[1];
        },
        // Fix for starting with negative decimal
        'onKeyDown': function(event, buffer, _caretPos, _opts) {
            var currentValue = buffer.length == 2 ? buffer[0] : "";
            if (currentValue === "-" && (event.key === "Decimal" || event.key === ".")) $(event.currentTarget).val('-0..');
        }
    });
});
$(document).on("focus", ".longitude-mask", function(){
    $(this).inputmask('numeric', {
        'placeholder': '',
        'rightAlign': false,
        'integerDigits': 3,
        'min': -180,
        'max': 180,
        'digits': 8,
        'digitsOptional': true,
        'groupSeparator': ".",
        'radixPoint': ",",
        'autoGroup': true,
        'allowMinus': true,
        'removeMaskOnSubmit': true,
        'autoUnmask': true,
        // Fix decimal point on unmask
        'onUnMask': function(maskedValue, _unmaskedValue) {
            var x = maskedValue.split(',');
            if (x[1] === undefined) return x[0].replace(/\./g, '');
            else return x[0].replace(/\./g, '') + '.' + x[1];
        },
        // Fix for starting with negative decimal
        'onKeyDown': function(event, buffer, _caretPos, _opts) {
            var currentValue = buffer.length == 2 ? buffer[0] : "";
            if (currentValue === "-" && (event.key === "Decimal" || event.key === ".")) $(event.currentTarget).val('-0..');
        }
    });
});
$(document).on("focus", ".document-mask", function(){
    $(this).inputmask('text', {
        'mask': ['999.999.999-99', '99.999.999/9999-99'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme um CPF com 11 dígitos ou um CNPJ com 14 dígitos.");
                this.value = '';
            }
        },
    });
});
$(document).on("focus", ".national-id-mask", function(){
    $(this).inputmask('text', {
        'mask': ['99.999.999-9', 'AA-99.999.999'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme um RG no formato 99.999.999-9 ou AA-99.999.999.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".phone-mask", function(){
    $(this).inputmask('text', {
        'mask': ['(99) 9999-9999', '(99) 99999-9999'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme um telefone com DDD e 8 ou 9 dígitos.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".date-mask", function(){
    $(this).inputmask('text', {
        'mask': ['99/99/9999'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme uma data no formato dd/mm/aaaa.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".time-mask", function(){
    $(this).inputmask('text', {
        'mask': ['99:99'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme uma hora no formato hh:mm.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".time-with-seconds-mask", function(){
    $(this).inputmask('text', {
        'mask': ['99:99:99'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme uma duração no formato hh:mm:ss.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".datetime-mask", function(){
    $(this).inputmask('text', {
        'mask': ['99/99/9999 99:99'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme uma data e hora no formato dd/mm/aaaa hh:mm.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".vehicle-plate-mask", function(){
    $(this).inputmask('text', {
        'mask': ['AAA-9999'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme uma placa no formato AAA-9999.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".zipcode-mask", function(){
    $(this).inputmask('text', {
        'mask': ['99999-999'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme um CEP com 8 dígitos.");
                this.value = '';
            }
        }
    });
});
$(document).on("focus", ".state-mask", function(){
    $(this).inputmask('text', {
        'mask': ['AA'],
        'clearMaskOnLostFocus': true,
        'showMaskOnHover': false,
        'showMaskOnFocus': false,
        'rightAlign': false,
        'removeMaskOnSubmit': false,
        'autoUnmask': false,
        'onincomplete': function() {
            if (this.value) {
                alert("Valor inválido!\nInforme a sigla do estado com 2 dígitos.");
                this.value = '';
            }
        }
    });
});

/**
 * ========================================================================
 * Datetimepiker
 * ========================================================================
 */
$(document).on("focus", ".timepicker", function(){
    $(this).datetimepicker({
        locale: 'pt-br',
        format: "HH:mm",
        stepping: 1
    });
});
$(document).on("focus", ".timepicker-blockpast", function(){
    $(this).datetimepicker({
        locale: 'pt-br',
        useCurrent: false,
        format: "HH:mm",
        minDate: moment(),
        disabledDates: [moment()],
        stepping: 1
    });
});
$(document).on("focus", ".timepicker-withseconds", function(){
    $(this).datetimepicker({
        useCurrent: false,
        locale: "pt-br",
        format: "HH:mm:ss",
        stepping: 1,
        defaultDate: moment().startOf('day')
    });
});
$(document).on("focus", ".datepicker", function(){
    $(this).datetimepicker({
        locale: 'pt-br',
        format: "DD/MM/YYYY"
    });
});
$(document).on("focus", ".datepicker-blockpast", function(){
    $(this).datetimepicker({
        locale: 'pt-br',
        useCurrent: true,
        format: "DD/MM/YYYY",
    });
});
$(document).on("focus", ".datetimepicker", function(){
    $(this).datetimepicker({
        locale: 'pt-br',
        format: "DD/MM/YYYY HH:mm",
    });
});
$(document).on("focus", ".datetimepicker-blockpast", function(){
    $(this).datetimepicker({
        locale: 'pt-br',
        useCurrent: true,
        format: "DD/MM/YYYY HH:mm",
        minDate: moment()
    });
});
