
export function User()
{
  return {
    getExportFields() {
      return [
        { id: 'id', name: 'ID'},
        { id: 'first_name', name: 'First Name'},
        { id: 'middle_name', name: 'Middle Name'},
        { id: 'last_name', name: 'Last Name'},
        { id: 'type', name: 'Type'},
        { id: 'email', name: 'Email'},
        { id: 'mobile_number', name: 'Mobile Number'},
        { id: 'work_number', name: 'Work Number'},
        { id: 'designation', name: 'Designation'},
        { id: 'facebook', name: 'Facebook'},
        { id: 'twitter', name: 'Twitter'},
        { id: 'instagram', name: 'Instagram'},
        { id: 'linkedin', name: 'Linkedin'},
        { id: 'timezone', name: 'Timezone'},
        { id: 'website', name: 'Website'},
        { id: 'email_verified_at', name: 'Email Verified At'},
        { id: 'active', name: 'Active'},
        { id: 'report_to', name: 'Reports_to'},

        { id: 'created_at', name: 'Created At'},
        { id: 'creator_name', name: 'Created By', not_filterable: true},
        { id: 'updated_at', name: 'Updated At'},
        { id: 'updator_name', name: 'Updated By', not_filterable: true},
      ]
    },

    getSelectedColumns() {
      return ['id', 'name', 'first_name', 'last_name', 'type', 'middle_name',
              'email', 'mobile_number', 'work_number', 'designation',
              'social_media', 'timezone', 'website',
              'email_verified_at', 'active', 'report_to',
              'created_by', 'updated_by', 'created_at', 'updated_at',
              'creator_name', 'updator_name', 'approved_by',
            ]
    },
  }
}

