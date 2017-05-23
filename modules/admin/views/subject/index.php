<?php
// 定义标题和面包屑信息
$this->title = '科目信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--前面导航信息-->
<p id="me-table-buttons"></p>
<!--表格数据-->
<table class="table table-striped table-bordered table-hover" id="show-table"></table>

<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var arrConfig = {
        "passingScore": "及格分数",
        "time": "考试时间(分)",
        "judgmentNumber": "判断题数",
        "selectNumber": "单选题数",
        "multipleNumber": "多选题数",
        "shortNumber": "问答题数",
        "judgmentScore": "判断题分数",
        "selectScore": "单选题分数",
        "multipleScore": "多选题分数",
        "shortScore": "问答题目分数"
    }, defaultArrConfig = {
        "passingScore": 72,
        "time": 60,
        "judgmentNumber": 10,
        "selectNumber": 40,
        "multipleNumber": 30,
        "shortNumber": 5,
        "judgmentScore": 2,
        "selectScore": 2,
        "multipleScore": 3,
        "shortScore": 5
    };

    mt.extend({
        configCreate: function (params, defaultConfig) {
            if (!defaultConfig) defaultConfig = defaultArrConfig;
            var html = "<div class='col-sm-12'>";
            for (var x in arrConfig) {
                html += "<div class='form-group'>" +
                    "<label class='col-sm-3 control-label'>" + arrConfig[x] + "</label>" +
                    "<div class='col-sm-9'>" +
                    "<input type='text' name='" + params.name + "[" + x + "]' required='true' value='" + defaultConfig[x] + "' class='form-control'>" +
                    "</div>" +
                    "</div>";

            }

            return html + "</div>";
        }
    });

    function initConfig(defaultVal) {
        var $div = $(".div-config");
        for (var x in defaultVal) {
            $div.find("input[name=config\\["+x+"\\]]").val(defaultVal[x]);
        }
    }

    var arrCarType = <?=\yii\helpers\Json::encode($arrCarType)?>,
        arrStatus = <?=\yii\helpers\Json::encode(Yii::$app->params['status'])?>;
    var myTable = meTables({
        title: "科目信息",
        fileSelector: ["#icon-image"],
        table: {
            "aoColumns":[
                {"title": "id", "data": "id", "sName": "id", "edit": {"type": "hidden"}, "search": {"type": "text"}, "defaultOrder": "desc"},
                {"title": "所属类型", "data": "car_id", "sName": "car_id", "value": arrCarType,
                    "edit": {"type": "select", "required": true, "number": true},
                    "search": {"type": "select"},
                    "createdCell": function(td, data) {
                        $(td).html(arrCarType[data] ? arrCarType[data] : data);
                    }
                },
                {"title": "科目名称", "data": "name", "sName": "name", "edit": {"type": "text", "required":true,"rangelength":"[2, 255]"}, "search": {"type": "text"}},
                {"title": "科目说明", "data": "desc", "sName": "desc", "edit": {"type": "text", "required":true,"rangelength":"[2, 1000]"}, "bSortable": false, "isHide": true},
                {"title": "科目配置", "data": "config", "sName": "config", "edit": {"type": "config", "required":true,"rangelength":"[2, 1000]"}, "bSortable": false, "isHide": true},
                {"title": "图标", "data": "image", "sName": "image", "bSortable": false,
                    "edit": {"type": "file", "rangelength": "[2, 255]",
                        "options": {"id": "icon-image", "name": "UploadForm[image]", "input-type": "ace_file", "input-name": "image"}
                    },
                    "isHide": true
                },
                {"title": "排序", "data": "sort", "sName": "sort", "edit": {"type": "text", "value": 100, "required":true, "number": true}},
                {"data": "status", "sName":"status","title": "状态", "value" : arrStatus, "edit":{"type":"radio", "default": 1, "required": 1, "number": 1},"search":{"type":"select"}, "createdCell": mt.statusString},
                {"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell": mt.dateTimeString}
            ]
        }
    });

    var $image = null;
    mt.fn.extend({
        afterShow: function(data, child) {

            if (this.action !== "delete") {
                $image.ace_file_input("reset_input");
                var defaultVal = defaultArrConfig;
                if (this.action === "update") {
                    // 图片处理
                    if (!empty(data.image)) {
                        $image.ace_file_input("show_file_list", [data.image]);
                    }

                    // 配置文件
                    if (data.config) {
                        try {
                            defaultVal = $.parseJSON(data.config);
                        } catch (e) {

                        }
                    }
                }

                initConfig(defaultVal);
            }

            return true;
        }
    });

    $(function(){
        myTable.init();
        $image = $("#icon-image");
    });
</script>
<?php $this->endBlock(); ?>