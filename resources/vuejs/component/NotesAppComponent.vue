<!-- Vue component -->
<template>
  <div>
    <div class="row error-noti">
      <div class="col-md-12">
        <div class="alert alert-danger" v-if="errors">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <ul>
            <li v-for="error in errors">{{ error }}</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="card" style="min-height:266px;">
          <div class="card-body">
            <h4 class="header-title mb-3">Notities</h4>
            <div id="app_messages" class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th style="width: 200px;">Datum</th>
                    <th style="width: 200px;">LB datum</th>
                    <th>Bericht</th>
                    <th style="width: 20px;">Van</th>
                    <th style="width: 20px;">Naar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(note, index) in notes" class="">
                    <td>{{ note.created_at }}</td>
                    <td>{{ note.updated_at }}</td>
                    <td v-html="note.message">{{ note.message }}</td>
                    <td>{{ note.user_id }}</td>
                    <td>{{ note.user_id_to }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group col-md-4">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title mb-3">Toewijzen aan</h4>
            <select v-model="selected" class="form-control mb-2">
              <option disabled value="">selecteer alstublieft</option>
              <option v-for="(user, index) in users" :value="user.id">{{ user.name }}</option>
            </select>
            <div class="form-group " style="position: relative;">
              <textarea class="form-control mb-2" rows="3" name="message" id="message" v-model="msg" :html="msg"></textarea>
              <a class="btn btn-info float-right" v-on:click="addNote">Notitie Toevoegen</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ['notes', 'users', 'order_id','user_id', 'errors', 'url'],
  data() {
    return {
      selected: '',
      msg: '',
    }
  },
  methods: {
      addNote() {
        var _this=this;
          if (this.msg.length > 1) {
              axios.get(this.url, {
                  params: {
                      msg: _this.msg,
                      user_id: _this.user_id,
                      user_id_to: _this.selected,
                      order_id: _this.order_id,
                      product_id: 0
                  }
              }).then( (response) => {
                  var app_notes = this;
                  app_notes.temp_row = response.data;
                  if (response.data.response.return == 'Success') {
                      app_notes.notes.unshift({
                          message: app_notes.msg,
                          user_id: response.data.response.username,
                          user_id_to: response.data.response.username_to,
                          order_id: app_notes.order_id,
                          created_at: response.data.response.created_at,
                          updated_at: response.data.response.updated_at,
                      });
                      app_notes.msg = '';
                      app_notes.selected = '';
                      //$('.notes_count').html("" + app_notes.notes.length);
                      //console.log(app_notes.notes.length);
                      app_notes.$emit('row-updated',app_notes.notes);

                  }
                },
                (error) => {
                      app_notes.errors = error.response.data;
                      console.log(app_notes.errors);
                  }
                );
          }
      },
      removenote(index) {
          this.rows.splice(index, 1);
      },
  },
}
</script>
