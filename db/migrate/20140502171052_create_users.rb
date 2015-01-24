class CreateUsers < ActiveRecord::Migration
  def change
    create_table :users do |t|
      t.string :first_name
      t.string :last_name
      t.string :email
      t.boolean :vegetarian
      t.boolean :guest
      t.boolean :scr
      t.string :user

      t.timestamps
    end
  end
end
