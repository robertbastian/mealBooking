class ChangeDateFormatInMyTable < ActiveRecord::Migration
  def change
    change_column :meals, :type, :integer
    change_column :meals, :capacity, :integer
  end
end
