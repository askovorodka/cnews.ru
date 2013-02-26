Ext.Loader.setConfig({
	enabled : true
});
/*Ext.Loader.setConfig({
    enabled: true
});
Ext.Loader.setPath('Ext.ux', '/inc/js/ExtJS/examples/ux');

Ext.require([
    'Ext.tree.*',
    'Ext.data.*',
    'Ext.window.MessageBox'
]);

Ext.onReady(function() {
    var store = Ext.create('Ext.data.TreeStore', {
        proxy: {
            type: 'ajax',
            url: '/inc/js/ExtJS/examples/tree/check-nodes.json'
        },
        sorters: [{
            property: 'leaf',
            direction: 'ASC'
        }, {
            property: 'text',
            direction: 'ASC'
        }]
    });

    var tree = Ext.create('Ext.tree.Panel', {
        store: store,
        rootVisible: false,
        useArrows: true,
        frame: true,
        title: 'Check Tree',
        renderTo: 'ext_render',
        width: 200,
        height: 250,
        dockedItems: [{
            xtype: 'toolbar',
            items: {
                text: 'Get checked nodes',
                handler: function(){
                    var records = tree.getView().getChecked(),
                        names = [];
                    
                    Ext.Array.each(records, function(rec){
                        names.push(rec.get('text'));
                    });
                    
                    Ext.MessageBox.show({
                        title: 'Selected Nodes',
                        msg: names.join('<br />'),
                        icon: Ext.MessageBox.INFO
                    });
                }
            }
        }]
    });
});
*/


//ExtJS настройки
//Ext.Loader.setConfig({enabled : true});

//Ext.Loader.setPath('Ext', '/inc/js/ExtJS/examples');
/*Ext.Loader.setConfig({
    enabled: true
});
Ext.Loader.setPath('Ext.ux', '/inc/js/ExtJS/examples/ux');

Ext.require([
    //'Ext.Msg'
    'Ext.data.*',
    'Ext.grid.*',
    'Ext.tree.*',
    'Ext.ux.CheckColumn'    
]);

Ext.onReady(function(){
   //Ext.Msg.alert('Загаловок','Hello World');
	
	Ext.QuickTips.init();
	
    Ext.define('Task', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'task',     type: 'string'},
            {name: 'user',     type: 'string'},
            {name: 'duration', type: 'string'},
            {name: 'done',     type: 'boolean'}
        ]
    });
	
    var store = Ext.create('Ext.data.TreeStore', {
        model: 'Task',
        proxy: {
            type: 'ajax',
            //the store will get the content from the .json file
            url: '/inc/js/ExtJS/examples/tree/treegrid.json'
        },
        folderSort: true
    });
    
    //Ext.ux.tree.TreeGrid is no longer a Ux. You can simply use a tree.TreePanel
    var tree = Ext.create('Ext.tree.Panel', {
        title: 'Core Team Projects',
        width: 500,
        height: 300,
        renderTo: 'ext_render',
        collapsible: true,
        useArrows: true,
        rootVisible: false,
        store: store,
        multiSelect: true,
        singleExpand: true,
        //the 'columns' property is now 'headers'
        columns: [{
            xtype: 'treecolumn', //this is so we know which column will show the tree
            text: 'Task',
            flex: 2,
            sortable: true,
            dataIndex: 'task'
        },{
            //we must use the templateheader component so we can use a custom tpl
            xtype: 'templatecolumn',
            text: 'Duration',
            flex: 1,
            sortable: true,
            dataIndex: 'duration',
            align: 'center',
            //add in the custom tpl for the rows
            tpl: Ext.create('Ext.XTemplate', '{duration:this.formatHours}', {
                formatHours: function(v) {
                    if (v < 1) {
                        return Math.round(v * 60) + ' mins';
                    } else if (Math.floor(v) !== v) {
                        var min = v - Math.floor(v);
                        return Math.floor(v) + 'h ' + Math.round(min * 60) + 'm';
                    } else {
                        return v + ' hour' + (v === 1 ? '' : 's');
                    }
                }
            })
        },{
            text: 'Assigned To',
            flex: 1,
            dataIndex: 'user',
            sortable: true
        }, {
            xtype: 'checkcolumn',
            header: 'Done',
            dataIndex: 'done',
            width: 40,
            stopSelection: false
        }, {
            text: 'Edit',
            width: 40,
            menuDisabled: true,
            xtype: 'actioncolumn',
            tooltip: 'Edit task',
            align: 'center',
            icon: '/inc/js/ExtJS/examples/simple-tasks/resources/images/edit_task.png',
            handler: function(grid, rowIndex, colIndex, actionItem, event, record, row) {
                Ext.Msg.alert('Editing' + (record.get('done') ? ' completed task' : '') , record.get('task'));
            }
        }]
    });
    
    
});
*/