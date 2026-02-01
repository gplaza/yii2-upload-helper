(function($) {
    'use strict';
    
    if (typeof $.fn.fileinput === 'undefined') {
        console.error('bootstrap-fileinput doit être chargé avant ce fichier');
        return;
    }

    var tStyle = " {style}";
    var tCAD = '<iframe class="kv-preview-data file-preview-cad" ' + tStyle + ' src="//sharecad.org/cadframe/load?url={data}" scrolling="no"></iframe>';
    
    $.extend(true, $.fn.fileinput.defaults, {
        previewContentTemplates: {
            cad: tCAD
        },

        allowedPreviewTypes: ($.fn.fileinput.defaults.allowedPreviewTypes || []).concat(['cad']),

        fileTypeSettings: {
            cad: function(vType, vName) {
                return /\.(dwg|dxf|step|stp|iges|igs)$/i.test(vName);
            }
        },
        previewSettings: {
            cad: { width: '213px', height: '160px' }
        },
        previewZoomSettings: {
            cad: { width: '90%', height: '90%' }
        },
        previewSettingsSmall: {
            cad: { width: '480px', height: '480px' }
        }
    });    
})(window.jQuery);