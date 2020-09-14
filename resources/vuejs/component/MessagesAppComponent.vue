<!-- Vue component -->
<template>
<div>
  <div class="row">
      <div class="col-md-6">
          <div class="form-group">
              <label>Select Model</label>
              <select class="form-control" id="select-model" v-model="selected_model" @change="model_selected">
                  <option value="product">Product</option>
                  <option value="order">Order</option>
              </select>
          </div>
          <div class="form-group">
                <input type="text" class="form-control" placeholder="Search..." v-model="search" v-on:keyup="autoComplete" autocomplete="off" >
              <input type="hidden" v-model="selected_sub_model" id="select-sub-model">
               <ul id="searchResultsNotes"class="" aria-expanded="true" >
                  <li v-if="selected_model=='product'" v-for="models_id in models_ids" :id="models_id.id" v-on:click="select($event)">{{models_id.id }} - {{models_id.sku }}</li>
                  <li v-if="selected_model=='order'" v-for="models_id in models_ids" :id="models_id.id" v-on:click="select($event)">{{models_id.id }}</li>
                  
              </ul>
          </div>
          <div class="form-group">
              <label>Select User</label>
              <select class="form-control" id="select-user" v-model="selected_user">
                  <option v-for="user in users" :value="user.id">{{user.name}}</option>
              </select>
          </div>
      </div>
      <div class="col-md-6">
          <div class="form-group">
              <label>Bericht</label>
              <textarea class="form-control" rows="7" v-model="msg">{{msg }}</textarea>
          </div>
      </div>
      <div class="col-md-12">
          <button type="submit" class="btn btn-sm btn-success send-button" v-on:click="addMessage">Send</button>
      </div>
  </div>
</div>
</template>

<script>
export default {
    props: ['users','user_id', 'url','url_add'],
    data() {
      return {
        search:'',
        results:[],
        messages: [],
        msg: '',
        temp_row: '',
        selected: '',
        product_id: '',
        order_id: '',
        models_ids: [],
        errors: null,
        selected_sub_model: '',
        selected_model: '',
        selected_user: '',
        error: '',
      }
    },
    methods: {
        addMessage () {
            if (this.msg.length > 1 && this.selected_sub_model && this.selected_user && this.selected_model) {
                if (this.selected_model == 'product') {
                    this.product_id = this.selected_sub_model;
                    this.order_id = 0;
                } else if (this.selected_model == 'order') {
                    this.order_id = this.selected_sub_model;
                    this.product_id = 0;
                }
                var self = this;
                axios.get(this.url_add, {
                    params: {
                        msg: self.msg,
                        user_id: self.user_id,
                        user_id_to: self.selected_user,
                        order_id: self.order_id,
                        product_id: self.product_id
                    }
                }).then(response => {
                    if(response.data.response.return == 'Success')
                {
                    toastr.success("The meesage has been sent !");
                    $('#all_sent_notes_table').DataTable().ajax.reload();
                    $('#all_inbox_notes_table').DataTable().ajax.reload();
                    self.msg='';
                    self.selected_user='';
                    self.order_id='';
                    self.product_id='';
                    self.models_ids='';
                    self.selected_model='';
                    self.selected_sub_model='';
                    self.search='';
                }
            }).
                catch((error) => {
                    toastr.warning('Error');
                console.log(this.errors);
            });
            }else{
                var msgTmp='';
                var newLine = "\r\n"
                if(! this.selected_sub_model){
                    msgTmp='De bestelling of het product dat u hebt geselecteerd, bestaat niet'+newLine;
                }
                if(!this.selected_user){
                    msgTmp=msgTmp+'Selecteer een gebruiker'+newLine;
                }
                if( !this.selected_model ){
                    msgTmp=msgTmp+'Selecteer een product of bestelling'+newLine;
                }
                if( this.msg.length < 1 ){
                     msgTmp=msgTmp+'Schrijf een bericht'+newLine;
                }
                alert(msgTmp);
            }
        },
         autoComplete() {
            if(this.search.length > 0) {
                var _this = this;
                axios.get(this.url, {params: {selected_model: _this.selected_model,search: _this.search}})
                .then(response => {
                _this.temp_row = response.data;
                if (response.status == 200 && response.data.return == "Success") {
                    var models = [];
                    if (_this.selected_model=='product'){
                        response.data.model.forEach(function (element) {
                            models.push({
                                id: element.id,
                                sku: element.sku
                            });
                        });
                    } else{
                         response.data.model.forEach(function (element) {
                            models.push({
                                id: element,
                            });
                        });
                    }
                    _this.models_ids = models;
                }
            });
            }else this.results=[];
        },
        model_selected: function () {

        },
        select: function (event) {
            var targetId = event.currentTarget.id;
            this.selected_sub_model=targetId;
           
            var result = this.models_ids.find(obj => {
            return obj.id === targetId
            })
            if (this.selected_model == 'product'){
                this.search=result.id+' - '+result.sku;
            }
            else{
                this.search=result.id;
            }
            this.models_ids = '';

        },
    }
}
</script>
