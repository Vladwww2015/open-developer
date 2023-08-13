/*-------------------------------------------------*/
/* forms */
/*-------------------------------------------------*/

developer.actions = {


    init : function(){


    },

    add : function(action){

        /*
        developer.actions.add({
            selector : '{$this->selector($this->selectorPrefix)}',
            event :'{$this->event}',
            parameters : '{$parameters}',
            _action: '{$this->getCalledClass()}',
            url : '{$this->getHandleRoute()}',
            method : '{$ajaxMethod}',
            promise : {$this->preScript()}
        })
        */

        document.querySelectorAll(action.selector).forEach(el=>{

            el.addEventListener(action.event,function(){

                var data = el.dataset;
                var target = el;
                Object.assign(data, action.parameters);

                const myPromise = new Promise((resolve, reject) => {
                    action.pre(resolve,reject);
                });

                myPromise.then(function(){

                    Object.assign(data, {
                        _action: action._action
                    });
                    if (data["_key"] === undefined){
                        data._key =developer.grid.selected.join();
                    }

                    var url = action.url;
                    developer.ajax[action.method](url, data, function(data){
                        developer.actions.actionResolver([data,el]);
                    });
                }).catch(function(){
                    console.log("canceled")
                });

            });
        });
    },

    actionResolver : function (data) {

        var response = data[0].data;
        var target   = data[1];
        if (typeof response === 'string') {
            target.innerHTML = response;
        }else if (typeof response !== 'object') {

            Swal.fire({type: 'error', title: 'Oops!'});
            console.log(response);
        }

        var then = function (then) {
            if (then.action == 'refresh') {
                developer.ajax.reload();
            }

            if (then.action == 'download') {
                window.open(then.value, '_blank');
            }

            if (then.action == 'redirect') {
                developer.ajax.navigate(then.value);
            }

            if (then.action == 'location') {
                window.location = then.value;
            }

            if (then.action == 'open') {
                window.open(then.value, '_blank');
            }
        };

        if (typeof response.html === 'string') {
            target.innerHTML = response.html;
        }

        if (typeof response.swal === 'object') {
            Swal.fire(response.swal);
        }

        if (typeof response.toastr === 'object' && response.toastr.type) {
            developer.toastr[response.toastr.type](response.toastr.content, response.toastr.options);
        }

        if (response.then) {
          then(response.then);
        }
    },

    actionCatcher : function (request) {
        if (request && typeof request.responseJSON === 'object') {
            developer.toastr.error(request.responseJSON.message, {positionClass:"toast-bottom-center", timeOut: 10000}).css("width","500px")
        }
    }
}
