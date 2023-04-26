export default {
  isLoading: state => state.isLoading,
  listCustomerLead: state => state.boards,
  searchOption: state => state.searchOption,
  currentFilter: state => state.currentFilter,
  activeBoard: state => state.activeBoard,
  unarchivedBoards: state => state.boards.filter(b => !b.archived),
  archivedBoards: state => state.boards.filter(b => b.archived),
  archivedLists: state => (state.activeBoard ? state.activeBoard.lists.filter(l => l.archived) : []),
  unarchivedLists: state => (state.activeBoard ? state.activeBoard.lists.filter(l => !l.archived) : [])
}
