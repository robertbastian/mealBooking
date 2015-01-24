class ChangeDateFormatInMyTableAgain < ActiveRecord::Migration
  def change
    rename_column :meals, :type, :kind
  end
end
