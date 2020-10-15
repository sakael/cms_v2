<div class="tab-pane fade" id="main_history_tab" role="tabpanel">
  <div class="card no-margin">
      <div class="card-body">
          <table width="100%" class="table table-striped table-hover" id="repricerUpdates">
              <thead>
              <tr>
                  <th>datum</th>
                  <th>Taak</th>
                  <th>user</th>
              </tr>
              </thead>
              <tbody>
                {%for hist in history%}
                <tr>
                  <td>{{hist.created_at}}</td>
                  <td>{{hist.task}}</td>
                  <td>{{hist.name}} {{hist.lastname}}</td>
                </tr>
                {%endfor%}
              </tbody>
          </table>
      </div>
  </div>
</div>
